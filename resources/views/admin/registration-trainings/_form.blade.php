<form method="POST" action="{{ isset($registrationTraining) ? route('admin.registration-trainings.update', $registrationTraining) : route('admin.registration-trainings.store') }}">
    @csrf
    @if(isset($registrationTraining)) @method('PUT') @endif

    <div class="mb-3">
        <label class="form-label">Training Title *</label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $registrationTraining->title ?? '') }}" required
               placeholder="e.g. THREE (03) MONTHS MANDATORY PRE-PROMOTION/ MID CAREER TRAINING">
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $registrationTraining->description ?? '') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" min="0"
                   value="{{ old('sort_order', $registrationTraining->sort_order ?? 0) }}">
        </div>
        <div class="col-md-4 mb-3 d-flex align-items-end">
            <div class="form-check">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
                       {{ old('is_active', $registrationTraining->is_active ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (show on registration page)</label>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.registration-trainings.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
