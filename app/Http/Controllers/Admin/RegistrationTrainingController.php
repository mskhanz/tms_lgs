<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationTraining;
use Illuminate\Http\Request;

class RegistrationTrainingController extends Controller
{
    public function index()
    {
        $trainings = RegistrationTraining::withCount('trainees')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(20);

        return view('admin.registration-trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.registration-trainings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:registration_trainings,title',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        RegistrationTraining::create($validated);

        return redirect()->route('admin.registration-trainings.index')
            ->with('success', 'Registration training option created.');
    }

    public function edit(RegistrationTraining $registrationTraining)
    {
        return view('admin.registration-trainings.edit', compact('registrationTraining'));
    }

    public function update(Request $request, RegistrationTraining $registrationTraining)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:registration_trainings,title,' . $registrationTraining->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $registrationTraining->update($validated);

        return redirect()->route('admin.registration-trainings.index')
            ->with('success', 'Registration training option updated.');
    }

    public function destroy(RegistrationTraining $registrationTraining)
    {
        if ($registrationTraining->trainees()->exists()) {
            return back()->with('error', 'Cannot delete: trainees are registered for this training.');
        }

        $registrationTraining->delete();

        return redirect()->route('admin.registration-trainings.index')
            ->with('success', 'Registration training option deleted.');
    }
}
