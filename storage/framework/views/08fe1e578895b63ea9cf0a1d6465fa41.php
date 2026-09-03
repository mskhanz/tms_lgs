

<?php $__env->startSection('title', $trainee->name . ' - Profile'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="page-header no-print">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="bi bi-person me-2"></i>Trainee Profile</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.trainees.index')); ?>">Trainees</a></li>
                    <li class="breadcrumb-item active"><?php echo e($trainee->name); ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo e(route('admin.trainees.pdf', $trainee->id)); ?>" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
            </a>
            <button type="button" onclick="window.print()" class="btn btn-outline-primary">
                <i class="bi bi-printer me-2"></i>Print
            </button>
            <a href="<?php echo e(route('admin.trainees.edit', $trainee->id)); ?>" class="btn btn-primary">
                <i class="bi bi-pencil me-2"></i>Edit Profile
            </a>
        </div>
    </div>
</div>

<div class="print-header d-none d-print-block mb-4">
    <h2 class="mb-1"><?php echo e($profile?->emp_name ?? $trainee->name); ?></h2>
    <p class="text-muted mb-0">
        Trainee dossier · <?php echo e($trainee->email); ?>

        · Enrollments: <?php echo e($enrollmentSummaries->count()); ?>

        <?php if($attendanceOverview['totalSessions'] > 0): ?>
            · Attendance: <?php echo e(number_format($attendanceOverview['overallPercentage'], 1)); ?>%
        <?php endif; ?>
    </p>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Profile Card -->
        <div class="card mb-4">
            <div class="card-body text-center" style="padding: 2rem;">
                <?php if($trainee->photo): ?>
                    <img src="<?php echo e(asset('user_photos/' . $trainee->photo)); ?>" 
                         alt="<?php echo e($trainee->name); ?>" 
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #10b981;">
                <?php elseif($trainee->traineeProfile && $trainee->traineeProfile->file_picture && file_exists(public_path('trainee_pictures/' . $trainee->traineeProfile->file_picture))): ?>
                    <img src="<?php echo e(asset('trainee_pictures/' . $trainee->traineeProfile->file_picture)); ?>" 
                         alt="<?php echo e($trainee->name); ?>" 
                         class="rounded-circle mb-3"
                         style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #10b981;">
                <?php else: ?>
                    <div class="mx-auto mb-3" style="width: 150px; height: 150px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 4rem; font-weight: 600;">
                        <?php echo e(strtoupper(substr($trainee->name, 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <h2 class="mb-2"><?php echo e($trainee->name); ?></h2>
                <p class="text-muted mb-3">
                    <?php if($trainee->traineeProfile): ?>
                        <?php echo e($trainee->traineeProfile->designation ?? 'Trainee'); ?>

                    <?php else: ?>
                        Trainee
                    <?php endif; ?>
                </p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-<?php echo e($trainee->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($trainee->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                    <?php if($trainee->profile_completed): ?>
                        <span class="badge bg-info">Profile Complete</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Profile Incomplete</span>
                    <?php endif; ?>
                </div>
                <div class="row text-start">
                    <div class="col-md-6">
                        <p><strong><i class="bi bi-envelope me-2 text-primary"></i>Email:</strong><br>
                        <?php echo e($trainee->email); ?></p>
                    </div>
                    <?php if($trainee->traineeProfile): ?>
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-telephone me-2 text-success"></i>Contact:</strong><br>
                            <?php echo e($trainee->traineeProfile->contact_no ?? 'Not Set'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if($trainee->traineeProfile): ?>
        <!-- Personal Information -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Full Name:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->emp_name ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Father's Name:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->father_name ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">CNIC:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->cnic_no ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Personal Number:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->personal_no ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Gender:</strong></div>
                    <div class="col-md-8">
                        <?php if($trainee->traineeProfile->gender): ?>
                        <span class="badge bg-<?php echo e($trainee->traineeProfile->gender == 'male' ? 'primary' : ($trainee->traineeProfile->gender == 'female' ? 'danger' : 'secondary')); ?>">
                            <?php echo e(ucfirst($trainee->traineeProfile->gender)); ?>

                        </span>
                        <?php else: ?>
                        N/A
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Date of Birth:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->dob ? $trainee->traineeProfile->dob->format('M d, Y') : 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Domicile:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->domicile ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Trainee Type:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->trainee_type ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-telephone me-2"></i>Contact Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Email (Official):</strong></div>
                    <div class="col-md-8">
                        <i class="bi bi-envelope me-2"></i><?php echo e($trainee->traineeProfile->emp_email ?? 'N/A'); ?>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Contact Number:</strong></div>
                    <div class="col-md-8">
                        <i class="bi bi-telephone me-2"></i><?php echo e($trainee->traineeProfile->contact_no ?? 'N/A'); ?>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">WhatsApp Number:</strong></div>
                    <div class="col-md-8">
                        <i class="bi bi-whatsapp me-2"></i><?php echo e($trainee->traineeProfile->emp_whatsapp_no ?? 'N/A'); ?>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Permanent Address:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->permanent_address ?? 'N/A'); ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4"><strong class="text-muted">Current Address:</strong></div>
                    <div class="col-md-8"><?php echo e($trainee->traineeProfile->current_address ?? 'N/A'); ?></div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <?php
            $empProfile = $trainee->traineeProfile;
            $designationBps = $empProfile->designation ?? 'N/A';
            if ($empProfile->bps) {
                $designationBps .= ' · BPS-'.$empProfile->bps;
            }
        ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-building me-2"></i>Employment Details</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Organization</th>
                                <th>Designation / BPS</th>
                                <th>Cadre</th>
                                <th>Initial Appointment</th>
                                <th>Service Length</th>
                                <th>From Date</th>
                                <th>District</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($empProfile->organization->name ?? 'N/A'); ?></td>
                                <td><?php echo e($designationBps); ?></td>
                                <td><?php echo e($empProfile->cadre ?? 'N/A'); ?></td>
                                <td><?php echo e($empProfile->date_of_initial_appointment ? $empProfile->date_of_initial_appointment->format('M d, Y') : 'N/A'); ?></td>
                                <td><?php echo e($empProfile->serviceLengthLabel()); ?></td>
                                <td><?php echo e($empProfile->from_date ? $empProfile->from_date->format('M d, Y') : 'N/A'); ?></td>
                                <td><?php echo e($empProfile->district->name ?? 'N/A'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Qualifications -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-mortarboard me-2"></i>Academic Qualifications</h5>
            </div>
            <div class="card-body p-0">
                <?php if($trainee->traineeProfile->qualifications && $trainee->traineeProfile->qualifications->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Degree</th>
                                <th>Subject</th>
                                <th>Institute</th>
                                <th>Country</th>
                                <th>Year</th>
                                <th>Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $trainee->traineeProfile->qualifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $qualification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($qualification->degree->name ?? 'N/A'); ?></td>
                                <td><?php echo e($qualification->subject->name ?? 'N/A'); ?></td>
                                <td><?php echo e($qualification->institute ?? 'N/A'); ?></td>
                                <td><?php echo e($qualification->country->name ?? 'N/A'); ?></td>
                                <td><?php echo e($qualification->passing_year ?? 'N/A'); ?></td>
                                <td><?php echo e($qualification->marks_percentage ?? 'N/A'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center mb-0 py-4">No qualifications recorded.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Remarks -->
        <?php if($trainee->traineeProfile->remarks): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Additional Remarks</h5>
            </div>
            <div class="card-body">
                <p class="mb-0"><?php echo e($trainee->traineeProfile->remarks); ?></p>
            </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Profile Incomplete:</strong> This trainee has not completed their profile yet.
        </div>
        <?php endif; ?>

        <?php echo $__env->make('admin.trainees.partials.enrollment-attendance', [
            'enrollmentSummaries' => $enrollmentSummaries,
            'attendanceOverview' => $attendanceOverview,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4 no-print">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Total Enrollments</span>
                    <span class="badge bg-primary rounded-pill"><?php echo e($enrollmentSummaries->count()); ?></span>
                </div>
                <?php if($attendanceOverview['totalSessions'] > 0): ?>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Overall Attendance</span>
                    <span class="badge bg-success rounded-pill"><?php echo e(number_format($attendanceOverview['overallPercentage'], 1)); ?>%</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Marked Sessions</span>
                    <span class="fw-semibold"><?php echo e($attendanceOverview['presentCount']); ?>/<?php echo e($attendanceOverview['totalSessions']); ?></span>
                </div>
                <?php endif; ?>
                <?php if($trainee->traineeProfile): ?>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Qualifications</span>
                    <span class="badge bg-primary rounded-pill"><?php echo e($trainee->traineeProfile->qualifications->count()); ?></span>
                </div>
                <?php endif; ?>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Profile Status</span>
                    <?php if($trainee->profile_completed): ?>
                        <span class="badge bg-success">Complete</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Incomplete</span>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <span class="text-muted">Account Status</span>
                    <span class="badge bg-<?php echo e($trainee->is_active ? 'success' : 'secondary'); ?>">
                        <?php echo e($trainee->is_active ? 'Active' : 'Inactive'); ?>

                    </span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">Joined Date</span>
                    <span class="fw-semibold"><?php echo e($trainee->created_at->format('d M, Y')); ?></span>
                </div>
            </div>
        </div>

        <!-- Profile Completion Progress -->
        <?php if($trainee->traineeProfile): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Profile Completion</h5>
            </div>
            <div class="card-body">
                <?php
                    $profile = $trainee->traineeProfile;
                    $fields = [
                        'emp_name', 'father_name', 'cnic_no', 'gender', 'dob',
                        'contact_no', 'designation', 'bps', 'organization_id', 'district_id'
                    ];
                    $completed = 0;
                    foreach($fields as $field) {
                        if(!empty($profile->$field)) $completed++;
                    }
                    $percentage = round(($completed / count($fields)) * 100);
                ?>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><?php echo e($percentage); ?>% Complete</span>
                        <span><?php echo e($completed); ?>/<?php echo e(count($fields)); ?> fields</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" 
                             role="progressbar" 
                             style="width: <?php echo e($percentage); ?>%" 
                             aria-valuenow="<?php echo e($percentage); ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                    </div>
                </div>
                <?php if($percentage < 100): ?>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Profile needs completion for full functionality.
                </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Profile Metadata -->
        <?php if($trainee->traineeProfile->completed_at): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Profile Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <small class="text-muted">Completed Date</small>
                    <p class="fw-semibold mb-0"><?php echo e($trainee->traineeProfile->completed_at->format('M d, Y h:i A')); ?></p>
                </div>
                <?php if($trainee->traineeProfile->completedBy): ?>
                <div class="mb-3 pb-3 border-bottom">
                    <small class="text-muted">Completed By</small>
                    <p class="fw-semibold mb-0"><?php echo e($trainee->traineeProfile->completedBy->name); ?></p>
                </div>
                <?php endif; ?>
                <?php if($trainee->traineeProfile->updatedBy): ?>
                <div>
                    <small class="text-muted">Last Updated By</small>
                    <p class="fw-semibold mb-0"><?php echo e($trainee->traineeProfile->updatedBy->name); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <!-- Assigned Roles -->
        <?php if($trainee->roles && $trainee->roles->count() > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Assigned Roles</h5>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $trainee->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-primary mb-2"><?php echo e($role->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <a href="<?php echo e(route('admin.trainees.pdf', $trainee->id)); ?>" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Download PDF
                </a>
                <button type="button" onclick="window.print()" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-printer me-2"></i>Print Profile
                </button>
                <a href="<?php echo e(route('admin.trainees.edit', $trainee->id)); ?>" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </a>
                <a href="<?php echo e(route('admin.enrollments.create')); ?>?trainee_id=<?php echo e($trainee->id); ?>" class="btn btn-outline-success w-100 mb-2">
                    <i class="bi bi-plus-circle me-2"></i>Enroll in Training
                </a>
                <a href="<?php echo e(route('admin.trainees.index')); ?>" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left me-2"></i>Back to List
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .btn-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
    @media print {
        .no-print,
        .sidebar,
        .page-header,
        .alert {
            display: none !important;
        }
        .col-lg-8 {
            width: 100% !important;
            max-width: 100% !important;
        }
        .card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        .attendance-panel {
            background: #fff !important;
        }
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tms_lgs\resources\views/admin/trainees/show.blade.php ENDPATH**/ ?>