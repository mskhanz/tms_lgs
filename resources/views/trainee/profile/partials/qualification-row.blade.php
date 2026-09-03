@php
    $row = $row ?? [];
    $rowId = $row['id'] ?? '';
    $degreeId = $row['degree_id'] ?? '';
    $subjectId = $row['subject_id'] ?? '';
    $institute = $row['institute'] ?? '';
    $countryId = $row['country_id'] ?? ($defaultCountryId ?? '');
    $passingYear = $row['passing_year'] ?? '';
    $percentageMarks = $row['percentage_marks'] ?? '';
    $currentYear = (int) date('Y') + 1;
@endphp
<div class="qualification-row border rounded p-3 mb-3">
    <input type="hidden" name="qualifications[{{ $index }}][id]" value="{{ $rowId }}">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong class="text-secondary qualification-row-label">Qualification</strong>
        <button type="button" class="btn btn-sm btn-outline-danger remove-qualification" title="Remove this qualification">
            <i class="bi bi-trash me-1"></i>Remove
        </button>
    </div>
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <label class="form-label">Degree</label>
            <select name="qualifications[{{ $index }}][degree_id]"
                    class="form-select @error('qualifications.'.$index.'.degree_id') is-invalid @enderror">
                <option value="">Select degree</option>
                @foreach($degrees as $degree)
                    <option value="{{ $degree->id }}" {{ (string) $degreeId === (string) $degree->id ? 'selected' : '' }}>
                        {{ $degree->name }}
                    </option>
                @endforeach
            </select>
            @error('qualifications.'.$index.'.degree_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-lg-3">
            <label class="form-label">Subject / discipline</label>
            <select name="qualifications[{{ $index }}][subject_id]"
                    class="form-select @error('qualifications.'.$index.'.subject_id') is-invalid @enderror">
                <option value="">Select subject</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                @endforeach
            </select>
            @error('qualifications.'.$index.'.subject_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-lg-6">
            <label class="form-label">Institute</label>
            <input type="text"
                   name="qualifications[{{ $index }}][institute]"
                   value="{{ $institute }}"
                   class="form-control @error('qualifications.'.$index.'.institute') is-invalid @enderror"
                   placeholder="University / college / board">
            @error('qualifications.'.$index.'.institute')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-lg-3">
            <label class="form-label">Country</label>
            <select name="qualifications[{{ $index }}][country_id]"
                    class="form-select @error('qualifications.'.$index.'.country_id') is-invalid @enderror">
                <option value="">Select country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ (string) $countryId === (string) $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
            @error('qualifications.'.$index.'.country_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-lg-3">
            <label class="form-label">Passing year</label>
            <input type="number"
                   name="qualifications[{{ $index }}][passing_year]"
                   value="{{ $passingYear }}"
                   class="form-control @error('qualifications.'.$index.'.passing_year') is-invalid @enderror"
                   min="1950"
                   max="{{ $currentYear }}"
                   placeholder="YYYY">
            @error('qualifications.'.$index.'.passing_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-lg-3">
            <label class="form-label">Marks / percentage</label>
            <input type="text"
                   name="qualifications[{{ $index }}][percentage_marks]"
                   value="{{ $percentageMarks }}"
                   class="form-control @error('qualifications.'.$index.'.percentage_marks') is-invalid @enderror"
                   placeholder="e.g. 78% or 3.5 GPA"
                   maxlength="20">
            @error('qualifications.'.$index.'.percentage_marks')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
