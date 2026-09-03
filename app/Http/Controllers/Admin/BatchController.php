<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{TrainingBatch, TrainingProgram, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BatchController extends Controller
{
    public function index(Request $request)
    {
        $query = TrainingBatch::with(['trainingProgram', 'coordinator'])->withCount('enrollments');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_code', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhereHas('trainingProgram', function ($programQuery) use ($search) {
                        $programQuery->where('title', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('training_program_id')) {
            $query->where('training_program_id', $request->training_program_id);
        }

        $batches = $query->latest('start_date')->paginate(15)->withQueryString();
        $programs = TrainingProgram::orderBy('title')->get(['id', 'code', 'title']);

        return view('admin.batches.index', compact('batches', 'programs'));
    }

    public function create(Request $request)
    {
        return view('admin.batches.create', $this->formData($request->training_program_id));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedBatch($request);
        $validated['seats_filled'] = 0;
        $validated = $this->nullEmptyBatchFields($validated);

        $batch = TrainingBatch::create($validated);

        activity()
            ->performedOn($batch)
            ->causedBy(Auth::user())
            ->log('Training batch created');

        return redirect()->route('admin.batches.show', $batch)
            ->with('success', 'Training batch created successfully!');
    }

    public function show($id)
    {
        $batch = TrainingBatch::with([
            'trainingProgram',
            'coordinator',
            'enrollments.trainee',
        ])->withCount('enrollments')->findOrFail($id);

        return view('admin.batches.show', compact('batch'));
    }

    public function edit($id)
    {
        $batch = TrainingBatch::findOrFail($id);

        return view('admin.batches.edit', array_merge(
            compact('batch'),
            $this->formData($batch->training_program_id)
        ));
    }

    public function update(Request $request, $id)
    {
        $batch = TrainingBatch::findOrFail($id);
        $validated = $this->validatedBatch($request, $batch->id);

        if ((int) $validated['total_seats'] < (int) $batch->seats_filled) {
            return back()
                ->withInput()
                ->withErrors(['total_seats' => 'Total seats cannot be less than seats already filled ('.$batch->seats_filled.').']);
        }

        $validated = $this->nullEmptyBatchFields($validated);
        $batch->update($validated);

        activity()
            ->performedOn($batch)
            ->causedBy(Auth::user())
            ->log('Training batch updated');

        return redirect()->route('admin.batches.show', $batch)
            ->with('success', 'Training batch updated successfully!');
    }

    public function destroy($id)
    {
        $batch = TrainingBatch::findOrFail($id);

        if ($batch->enrollments()->count() > 0) {
            return back()->with('error', 'Cannot delete a batch that has enrollments.');
        }

        activity()
            ->performedOn($batch)
            ->causedBy(Auth::user())
            ->log('Training batch deleted');

        $batch->delete();

        return redirect()->route('admin.batches.index')
            ->with('success', 'Training batch deleted successfully!');
    }

    private function formData($selectedProgramId = null): array
    {
        return [
            'programs' => TrainingProgram::orderBy('title')->get(['id', 'code', 'title', 'attendance_enabled', 'min_attendance_percentage']),
            'coordinators' => User::where('is_active', true)
                ->where('user_type', '!=', 'trainee')
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
            'selectedProgramId' => $selectedProgramId,
            'selectedProgram' => $selectedProgramId
                ? TrainingProgram::find($selectedProgramId, ['id', 'attendance_enabled', 'min_attendance_percentage'])
                : null,
        ];
    }

    private function validatedBatch(Request $request, $batchId = null): array
    {
        return $request->validate([
            'training_program_id' => 'required|exists:training_programs,id',
            'batch_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('training_batches', 'batch_code')->ignore($batchId),
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'total_seats' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'attendance_enabled' => 'nullable|boolean',
            'min_attendance_percentage' => 'nullable|integer|min:0|max:100',
            'remarks' => 'nullable|string',
            'coordinator_id' => 'nullable|exists:users,id',
        ]);
    }

    private function nullEmptyBatchFields(array $validated): array
    {
        $validated['attendance_enabled'] = (bool) ($validated['attendance_enabled'] ?? false);

        if (($validated['min_attendance_percentage'] ?? '') === '') {
            $validated['min_attendance_percentage'] = null;
        }

        foreach (['venue', 'venue_address', 'remarks', 'coordinator_id'] as $field) {
            if (! array_key_exists($field, $validated) || $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        return $validated;
    }
}
