<form method="POST"
      action="{{ $assignment ? route('admin.assignments.update', $assignment) : route('admin.assignments.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if($assignment) @method('PUT') @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Title *</label>
            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', $assignment->title ?? '') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Total marks *</label>
            <input type="number" name="total_marks" class="form-control @error('total_marks') is-invalid @enderror"
                   value="{{ old('total_marks', $assignment->total_marks ?? 100) }}" min="1" max="9999" step="0.01" required>
            @error('total_marks')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3">
            <label class="form-label">Due date</label>
            <input type="datetime-local" name="due_at" class="form-control"
                   value="{{ old('due_at', $assignment?->due_at?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-12">
            <label class="form-label">Instructions</label>
            <textarea name="instructions" class="form-control" rows="5">{{ old('instructions', $assignment->instructions ?? '') }}</textarea>
            @error('instructions')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <label class="form-label">Assign to *</label>
            <div class="d-flex flex-wrap gap-4 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_program" value="program"
                           {{ old('assign_to', $assignment->assign_to ?? 'program') === 'program' ? 'checked' : '' }} required>
                    <label class="form-check-label" for="assign_to_program">Training Program</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="assign_to" id="assign_to_batch" value="batch"
                           {{ old('assign_to', $assignment->assign_to ?? '') === 'batch' ? 'checked' : '' }}>
                    <label class="form-check-label" for="assign_to_batch">Training Batch</label>
                </div>
            </div>
            <div class="form-text">Only enrolled trainees in the selected program or batch will see this assignment.</div>
            @error('assign_to')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6" id="programAssignmentWrap">
            <label class="form-label">Training Program *</label>
            <select name="training_program_id" id="training_program_id" class="form-select @error('training_program_id') is-invalid @enderror">
                <option value="">-- Select Program --</option>
                @foreach($programs as $program)
                    <option value="{{ $program->id }}" {{ (string) old('training_program_id', $assignment->training_program_id ?? '') === (string) $program->id ? 'selected' : '' }}>
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
                    <option value="{{ $batch->id }}" {{ (string) old('training_batch_id', $assignment->training_batch_id ?? '') === (string) $batch->id ? 'selected' : '' }}>
                        {{ $batch->batch_code }} — {{ $batch->trainingProgram->title ?? 'Program' }}
                    </option>
                @endforeach
            </select>
            @error('training_batch_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Available From</label>
            <input type="datetime-local" name="available_from" class="form-control"
                   value="{{ old('available_from', $assignment?->available_from?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Available Until</label>
            <input type="datetime-local" name="available_until" class="form-control"
                   value="{{ old('available_until', $assignment?->available_until?->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-4">
            <div class="form-check mt-4">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ old('is_active', $assignment?->is_active ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (notify &amp; show to trainees)</label>
            </div>
        </div>

        @if($assignment && $assignment->attachments->count())
        <div class="col-12">
            <label class="form-label">Current materials</label>
            <div class="d-flex flex-column gap-2">
                @foreach($assignment->attachments as $file)
                <div class="border rounded p-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Material name</label>
                            <input type="text" name="existing_titles[{{ $file->id }}]" class="form-control"
                                   value="{{ old('existing_titles.'.$file->id, $file->title ?: $file->original_name) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small mb-1">File</label>
                            <div class="form-control-plaintext small">
                                <i class="bi bi-paperclip me-1"></i>{{ $file->original_name }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remove_attachments[]" value="{{ $file->id }}" id="rm_{{ $file->id }}">
                                <label class="form-check-label text-danger" for="rm_{{ $file->id }}">Remove</label>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Upload materials (Word, PDF, images)</label>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-material-row">
                    <i class="bi bi-plus-circle me-1"></i>Add another file
                </button>
            </div>
            <div id="materials-rows" class="d-flex flex-column gap-2">
                <div class="border rounded p-3 material-row">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small mb-1">Material name *</label>
                            <input type="text" name="materials[0][title]" class="form-control" placeholder="e.g. Guidelines, Template, Sample">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small mb-1">File</label>
                            <input type="file" name="materials[0][file]" class="form-control"
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger w-100 remove-material-row" title="Remove" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-text mt-2">Add one or more files. Each file should have a name. Allowed: pdf, doc, docx, jpg, png, webp. Max 10 MB each.</div>
            @error('materials.*.file')<div class="text-danger small">{{ $message }}</div>@enderror
            @error('materials.*.title')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle me-1"></i>{{ $assignment ? 'Update Assignment' : 'Create Assignment' }}
        </button>
        <a href="{{ route('admin.assignments.index') }}" class="btn btn-outline-secondary">Cancel</a>
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

    const rowsWrap = document.getElementById('materials-rows');
    const addBtn = document.getElementById('add-material-row');
    let materialIndex = rowsWrap.querySelectorAll('.material-row').length;

    function refreshRemoveButtons() {
        const rows = rowsWrap.querySelectorAll('.material-row');
        rows.forEach(function (row) {
            const btn = row.querySelector('.remove-material-row');
            if (btn) {
                btn.disabled = rows.length <= 1;
            }
        });
    }

    function bindRemove(btn) {
        btn.addEventListener('click', function () {
            const rows = rowsWrap.querySelectorAll('.material-row');
            if (rows.length <= 1) {
                return;
            }
            btn.closest('.material-row').remove();
            refreshRemoveButtons();
        });
    }

    rowsWrap.querySelectorAll('.remove-material-row').forEach(bindRemove);

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'border rounded p-3 material-row';
        row.innerHTML =
            '<div class="row g-2 align-items-end">' +
                '<div class="col-md-5">' +
                    '<label class="form-label small mb-1">Material name *</label>' +
                    '<input type="text" name="materials[' + materialIndex + '][title]" class="form-control" placeholder="e.g. Guidelines, Template, Sample">' +
                '</div>' +
                '<div class="col-md-6">' +
                    '<label class="form-label small mb-1">File</label>' +
                    '<input type="file" name="materials[' + materialIndex + '][file]" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">' +
                '</div>' +
                '<div class="col-md-1">' +
                    '<button type="button" class="btn btn-outline-danger w-100 remove-material-row" title="Remove"><i class="bi bi-trash"></i></button>' +
                '</div>' +
            '</div>';
        rowsWrap.appendChild(row);
        bindRemove(row.querySelector('.remove-material-row'));
        materialIndex++;
        refreshRemoveButtons();
    });

    refreshRemoveButtons();
});
</script>
@endpush
