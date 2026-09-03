<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgram;
use App\Support\LookupCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingProgram::with(['conductingOrganization', 'createdBy'])->withCount('batches');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $programs = $query->latest()->paginate(15);
        
        return view('admin.programs.index', compact('programs'));
    }
    
    public function create()
    {
        $organizations = LookupCache::organizations();
        return view('admin.programs.create', compact('organizations'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:training_programs,code',
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'category' => 'required|in:technical,leadership,management,specialized,soft_skills,mid_career_training,pre_service_training,pre_promotion_training,others',
            'type' => 'required|in:orientation,induction,refresher,specialized,advanced',
            'duration_days' => 'required|integer|min:1',
            'duration_hours' => 'required|integer|min:1',
            'budget_allocated' => 'nullable|numeric|min:0',
            'objectives' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1',
            'conducting_organization_id' => 'nullable|exists:organizations,id',
            'attendance_enabled' => 'nullable|boolean',
            'min_attendance_percentage' => 'nullable|integer|min:0|max:100',
        ]);
        
        $validated['created_by'] = Auth::id();
        $validated['status'] = 'draft';
        $validated = $this->nullEmptyProgramFields($validated);
        $validated['attendance_enabled'] = (bool) ($validated['attendance_enabled'] ?? false);

        $program = TrainingProgram::create($validated);
        
        // Log activity
        activity()
            ->performedOn($program)
            ->causedBy(Auth::user())
            ->log('Training program created');
        
        return redirect()->route('admin.programs.index')
            ->with('success', 'Training program created successfully!');
    }
    
    public function show($id)
    {
        $program = TrainingProgram::with([
            'conductingOrganization',
            'batches.enrollments',
            'createdBy',
            'approvedBy'
        ])->findOrFail($id);
        
        return view('admin.programs.show', compact('program'));
    }
    
    public function edit($id)
    {
        $program = TrainingProgram::findOrFail($id);
        $organizations = LookupCache::organizations();
        
        return view('admin.programs.edit', compact('program', 'organizations'));
    }
    
    public function update(Request $request, $id)
    {
        $program = TrainingProgram::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:training_programs,code,' . $id,
            'title' => 'required|string|max:500',
            'description' => 'required|string',
            'category' => 'required|in:technical,leadership,management,specialized,soft_skills,mid_career_training,pre_service_training,pre_promotion_training,others',
            'type' => 'required|in:orientation,induction,refresher,specialized,advanced',
            'duration_days' => 'required|integer|min:1',
            'duration_hours' => 'required|integer|min:1',
            'budget_allocated' => 'nullable|numeric|min:0',
            'objectives' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'max_participants' => 'nullable|integer|min:1',
            'min_participants' => 'nullable|integer|min:1',
            'conducting_organization_id' => 'nullable|exists:organizations,id',
            'status' => 'required|in:draft,pending_approval,approved,ongoing,completed,cancelled,archived,active,inactive',
            'attendance_enabled' => 'nullable|boolean',
            'min_attendance_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $validated = $this->nullEmptyProgramFields($validated);
        $validated['attendance_enabled'] = (bool) ($validated['attendance_enabled'] ?? false);

        $program->update($validated);
        
        // Log activity
        activity()
            ->performedOn($program)
            ->causedBy(Auth::user())
            ->log('Training program updated');
        
        return redirect()->route('admin.programs.index')
            ->with('success', 'Training program updated successfully!');
    }
    
    public function destroy($id)
    {
        $program = TrainingProgram::findOrFail($id);
        
        // Check if program has batches
        if ($program->batches()->count() > 0) {
            return back()->with('error', 'Cannot delete program with existing batches!');
        }
        
        // Log activity before deletion
        activity()
            ->performedOn($program)
            ->causedBy(Auth::user())
            ->log('Training program deleted');
        
        $program->delete();
        
        return redirect()->route('admin.programs.index')
            ->with('success', 'Training program deleted successfully!');
    }

    private function nullEmptyProgramFields(array $validated): array
    {
        foreach (['budget_allocated', 'objectives', 'target_audience', 'max_participants', 'min_participants', 'conducting_organization_id', 'min_attendance_percentage'] as $field) {
            if (! array_key_exists($field, $validated) || $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        return $validated;
    }
}
