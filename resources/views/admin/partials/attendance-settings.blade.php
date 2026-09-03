@php
    $attendanceEnabled = (bool) old('attendance_enabled', $model->attendance_enabled ?? false);
    $minAttendance = old('min_attendance_percentage', $model->min_attendance_percentage ?? '');
@endphp

<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Attendance settings</h5>
    </div>
    <div class="card-body">
        <div class="form-check form-switch mb-3">
            <input type="hidden" name="attendance_enabled" value="0">
            <input class="form-check-input"
                   type="checkbox"
                   role="switch"
                   name="attendance_enabled"
                   id="attendance_enabled"
                   value="1"
                   {{ $attendanceEnabled ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="attendance_enabled">
                Enable attendance tracking
            </label>
        </div>
        <p class="text-muted small mb-3">
            When enabled, {{ $context === 'batch' ? 'this batch' : 'batches under this program' }} can record session-wise trainee attendance.
        </p>
        <div class="mb-0">
            <label for="min_attendance_percentage" class="form-label fw-semibold">
                Minimum attendance (%)
            </label>
            <input type="number"
                   name="min_attendance_percentage"
                   id="min_attendance_percentage"
                   class="form-control @error('min_attendance_percentage') is-invalid @enderror"
                   value="{{ $minAttendance }}"
                   min="0"
                   max="100"
                   placeholder="e.g. 75">
            @error('min_attendance_percentage')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Optional minimum attendance required for completion or certificates.</div>
        </div>
    </div>
</div>
