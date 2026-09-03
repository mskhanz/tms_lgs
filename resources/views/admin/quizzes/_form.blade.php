<form method="POST" action="{{ $quiz ? route('admin.quizzes.update', $quiz) : route('admin.quizzes.store') }}">
    @csrf
    @if($quiz) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label">Quiz Title *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $quiz->title ?? '') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Duration (minutes)</label>
            <input type="number" name="duration_minutes" class="form-control" min="1"
                   value="{{ old('duration_minutes', $quiz->duration_minutes ?? 90) }}" placeholder="e.g. 90">
        </div>
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $quiz->description ?? '') }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">Assign quiz to *</label>
            <div class="d-flex flex-wrap gap-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_program" value="program"
                           {{ old('assign_to', $quiz->assign_to ?? 'program') === 'program' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="assign_to_program">Training Program</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_batch" value="batch"
                           {{ old('assign_to', $quiz->assign_to ?? '') === 'batch' ? 'checked' : '' }}>
                    <label class="form-check-label" for="assign_to_batch">Training Batch</label>
                </div>
            </div>
            <div class="form-text">Only trainees enrolled in the selected program or batch will see and attempt this quiz.</div>
            @error('assign_to')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6" id="programAssignmentWrap">
            <label class="form-label">Training Program *</label>
            <select name="training_program_id" id="training_program_id" class="form-select @error('training_program_id') is-invalid @enderror">
                <option value="">-- Select Program --</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ (string) old('training_program_id', $quiz->training_program_id ?? '') === (string) $program->id ? 'selected' : '' }}>
                        {{ $program->code }} — {{ $program->title }}
                    </option>
                @endforeach
            </select>
            @error('training_program_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6" id="batchAssignmentWrap">
            <label class="form-label">Training Batch *</label>
            <select name="training_batch_id" id="training_batch_id" class="form-select @error('training_batch_id') is-invalid @enderror">
                <option value="">-- Select Batch --</option>
                @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" {{ (string) old('training_batch_id', $quiz->training_batch_id ?? '') === (string) $batch->id ? 'selected' : '' }}>
                        {{ $batch->batch_code }} — {{ $batch->trainingProgram->title ?? 'Program' }}
                    </option>
                @endforeach
            </select>
            @error('training_batch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Passing % *</label>
            <input type="number" name="passing_percentage" class="form-control" min="1" max="100"
                   value="{{ old('passing_percentage', $quiz->passing_percentage ?? 50) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Max Attempts *</label>
            <input type="number" name="max_attempts" class="form-control" min="1" max="10"
                   value="{{ old('max_attempts', $quiz->max_attempts ?? 1) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Available From</label>
            <input type="datetime-local" name="available_from" class="form-control"
                   value="{{ old('available_from', $quiz?->available_from?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Available Until</label>
            <input type="datetime-local" name="available_until" class="form-control"
                   value="{{ old('available_until', $quiz?->available_until?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="shuffle_questions" value="1" class="form-check-input" id="shuffle_questions"
                       {{ old('shuffle_questions', $quiz?->shuffle_questions ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="shuffle_questions">Shuffle Questions</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="shuffle_options" value="1" class="form-check-input" id="shuffle_options"
                       {{ old('shuffle_options', $quiz?->shuffle_options ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="shuffle_options">Shuffle Options</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="show_results" value="1" class="form-check-input" id="show_results"
                       {{ old('show_results', $quiz?->show_results ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="show_results">Show Results to Trainee</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ old('is_active', $quiz?->is_active ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (visible to trainees)</label>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>{{ $quiz ? 'Update Quiz' : 'Create Quiz' }}
        </button>
        <a href="{{ route('admin.quizzes.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const programWrap = document.getElementById('programAssignmentWrap');
    const batchWrap = document.getElementById('batchAssignmentWrap');
    const programSelect = document.getElementById('training_program_id');
    const batchSelect = document.getElementById('training_batch_id');

    function toggleAssignment() {
        const assignTo = document.querySelector('input[name="assign_to"]:checked')?.value;
        const isProgram = assignTo === 'program';

        programWrap.classList.toggle('d-none', !isProgram);
        batchWrap.classList.toggle('d-none', isProgram);
        programSelect.required = isProgram;
        batchSelect.required = !isProgram;
        programSelect.disabled = !isProgram;
        batchSelect.disabled = isProgram;
    }

    document.querySelectorAll('input[name="assign_to"]').forEach(function (input) {
        input.addEventListener('change', toggleAssignment);
    });

    toggleAssignment();
});
</script>
@endpush
