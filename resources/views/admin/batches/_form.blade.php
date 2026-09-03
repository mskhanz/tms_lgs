@php
    $selectedProgram = old('training_program_id', $batch?->training_program_id ?? $selectedProgramId);
    $selectedStatus = old('status', $batch?->status ?? 'scheduled');
    $selectedCoordinator = old('coordinator_id', $batch?->coordinator_id ?? '');
@endphp

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Batch details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="training_program_id" class="form-label fw-semibold">
                            Training Program <span class="text-danger">*</span>
                        </label>
                        <select name="training_program_id"
                                id="training_program_id"
                                class="form-select @error('training_program_id') is-invalid @enderror"
                                required>
                            <option value="">-- Select Program --</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}" {{ (string) $selectedProgram === (string) $program->id ? 'selected' : '' }}>
                                    {{ $program->code }} — {{ $program->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="batch_code" class="form-label fw-semibold">
                            Batch Code <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="batch_code"
                               id="batch_code"
                               class="form-control @error('batch_code') is-invalid @enderror"
                               value="{{ old('batch_code', $batch?->batch_code ?? '') }}"
                               placeholder="e.g., B-2026-001"
                               required>
                        @error('batch_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label fw-semibold">
                            Start Date <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               name="start_date"
                               id="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', $batch?->start_date?->format('Y-m-d') ?? '') }}"
                               required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label fw-semibold">
                            End Date <span class="text-danger">*</span>
                        </label>
                        <input type="date"
                               name="end_date"
                               id="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', $batch?->end_date?->format('Y-m-d') ?? '') }}"
                               required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="venue" class="form-label fw-semibold">Venue</label>
                    <input type="text"
                           name="venue"
                           id="venue"
                           class="form-control @error('venue') is-invalid @enderror"
                           value="{{ old('venue', $batch?->venue ?? '') }}"
                           placeholder="e.g., LGS Peshawar">
                    @error('venue')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-0">
                    <label for="venue_address" class="form-label fw-semibold">Venue Address</label>
                    <textarea name="venue_address"
                              id="venue_address"
                              class="form-control @error('venue_address') is-invalid @enderror"
                              rows="2"
                              placeholder="Full address...">{{ old('venue_address', $batch?->venue_address ?? '') }}</textarea>
                    @error('venue_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-people me-2"></i>Capacity & coordination</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="total_seats" class="form-label fw-semibold">
                            Total Seats <span class="text-danger">*</span>
                        </label>
                        <input type="number"
                               name="total_seats"
                               id="total_seats"
                               class="form-control @error('total_seats') is-invalid @enderror"
                               value="{{ old('total_seats', $batch?->total_seats ?? 30) }}"
                               min="1"
                               required>
                        @error('total_seats')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select name="status"
                                id="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                            @foreach(\App\Models\TrainingBatch::statusOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $selectedStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="coordinator_id" class="form-label fw-semibold">Coordinator</label>
                        <select name="coordinator_id"
                                id="coordinator_id"
                                class="form-select @error('coordinator_id') is-invalid @enderror">
                            <option value="">-- Select Coordinator --</option>
                            @foreach($coordinators as $coordinator)
                                <option value="{{ $coordinator->id }}" {{ (string) $selectedCoordinator === (string) $coordinator->id ? 'selected' : '' }}>
                                    {{ $coordinator->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('coordinator_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-0">
                    <label for="remarks" class="form-label fw-semibold">Remarks</label>
                    <textarea name="remarks"
                              id="remarks"
                              class="form-control @error('remarks') is-invalid @enderror"
                              rows="3"
                              placeholder="Optional notes for this batch...">{{ old('remarks', $batch?->remarks ?? '') }}</textarea>
                    @error('remarks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        @include('admin.partials.attendance-settings', [
            'model' => $batch ?? $selectedProgram ?? null,
            'context' => 'batch',
        ])

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>{{ $batch ? 'Update Batch' : 'Create Batch' }}
            </button>
            <a href="{{ $batch ? route('admin.batches.show', $batch) : route('admin.batches.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i>Cancel
            </a>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="bi bi-lightbulb me-2"></i>Batch guide</h6>
            </div>
            <div class="card-body small">
                <ul class="mb-0 ps-3">
                    <li>Link the batch to an existing training program</li>
                    <li>Use a unique batch code</li>
                    <li>End date must be on or after the start date</li>
                    <li>Seats filled are updated when trainees are enrolled</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label.fw-semibold {
        color: #047857;
    }
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .card-header.bg-info {
        background: linear-gradient(135deg, #14b8a6 0%, #0d9488 100%) !important;
    }
</style>
@endpush
