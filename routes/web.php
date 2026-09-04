<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{RegisteredUserController, AuthenticatedSessionController, EmailVerificationController, PasswordResetLinkController, NewPasswordController};
use App\Http\Controllers\AccountController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Trainee\{DashboardController as TraineeDashboardController, ProfileController, QuizController as TraineeQuizController, AttendanceController as TraineeAttendanceController, AssignmentController as TraineeAssignmentController};
use App\Http\Controllers\Admin\{DashboardController as AdminDashboardController, EnrollmentController, ProgramController, BatchController, AttendanceController, UserController, RoleController, TraineeController, QuizController, AssignmentController, RegistrationTrainingController, ActivityLogController, OnlineUserController, LoginHistoryController};

// Public routes
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return view('welcome');
})->name('home');

// Dashboard redirect route (email verification disabled)
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole(['system_admin', 'director', 'deputy_director', 'training_officer', 'department_admin', 'institute_admin'])) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isTrainer()) {
        return redirect()->route('trainer.dashboard');
    } else {
        return redirect()->route('trainee.dashboard');
    }
})->name('dashboard');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
    
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Email verification routes
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Logout and account routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::get('/account/profile/edit', [AccountController::class, 'edit'])->name('account.profile.edit');
    Route::put('/account/profile', [AccountController::class, 'update'])->name('account.profile.update');
    Route::get('/account/password', [AccountController::class, 'editPassword'])->name('account.password.edit');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});

// Trainee routes (email verification disabled)
Route::middleware(['auth'])->prefix('trainee')->name('trainee.')->group(function () {
    Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/pdf', [ProfileController::class, 'downloadPdf'])->name('profile.pdf');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/check-cnic', [ProfileController::class, 'checkCnic'])->name('profile.check-cnic');

    Route::get('/attendance', [TraineeAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/{enrollment}', [TraineeAttendanceController::class, 'show'])->name('attendance.show');

    // Quiz routes
    Route::get('/quizzes', [TraineeQuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}/start', [TraineeQuizController::class, 'start'])->name('quizzes.start');
    Route::get('/quizzes/attempt/{attempt}/take', [TraineeQuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/attempt/{attempt}/save', [TraineeQuizController::class, 'saveProgress'])->name('quizzes.save');
    Route::post('/quizzes/attempt/{attempt}/submit', [TraineeQuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/attempt/{attempt}/result', [TraineeQuizController::class, 'result'])->name('quizzes.result');

    // Assignment routes
    Route::get('/assignments', [TraineeAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/{assignment}', [TraineeAssignmentController::class, 'show'])->name('assignments.show');
    Route::post('/assignments/{assignment}/submit', [TraineeAssignmentController::class, 'submit'])->name('assignments.submit');
    Route::get('/assignments/{assignment}/attachments/{attachment}/download', [TraineeAssignmentController::class, 'downloadAttachment'])->name('assignments.attachments.download');
    Route::get('/assignments/{assignment}/attachments/{attachment}/view', [TraineeAssignmentController::class, 'viewAttachment'])->name('assignments.attachments.view');
    Route::get('/assignments/{assignment}/files/{file}/download', [TraineeAssignmentController::class, 'downloadSubmissionFile'])->name('assignments.files.download');
    Route::get('/assignments/{assignment}/files/{file}/view', [TraineeAssignmentController::class, 'viewSubmissionFile'])->name('assignments.files.view');
});

// Admin routes (System Admin, Director, Deputy Director, Training Officer) - email verification disabled
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Program Management
    Route::resource('programs', ProgramController::class);

    // Training Batches
    Route::resource('batches', BatchController::class);

    // Attendance
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/session-report', [AttendanceController::class, 'sessionReport'])->name('attendance.session-report');
    Route::post('/programs/{program}/attendance/toggle', [AttendanceController::class, 'toggleProgram'])->name('programs.attendance.toggle');
    Route::post('/programs/{program}/attendance/activate-batches', [AttendanceController::class, 'activateProgramBatches'])->name('programs.attendance.activate-batches');
    Route::post('/batches/{batch}/attendance/toggle', [AttendanceController::class, 'toggleBatch'])->name('batches.attendance.toggle');
    Route::get('/batches/{batch}/attendance', [AttendanceController::class, 'showBatch'])->name('batches.attendance.show');
    Route::post('/batches/{batch}/attendance/sessions', [AttendanceController::class, 'storeSession'])->name('batches.attendance.sessions.store');
    Route::get('/batches/{batch}/attendance/sessions/{session}/mark', [AttendanceController::class, 'markSession'])->name('batches.attendance.sessions.mark');
    Route::post('/batches/{batch}/attendance/sessions/{session}/mark', [AttendanceController::class, 'saveMarks'])->name('batches.attendance.sessions.save');
    
    // Enrollment Management
    Route::resource('enrollments', EnrollmentController::class);
    Route::post('/enrollments/{id}/update-status', [EnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');
    
    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // Roles & Permissions
    Route::resource('roles', RoleController::class);
    
    // Trainee Management
    Route::get('/trainees/{trainee}/pdf', [TraineeController::class, 'downloadPdf'])->name('trainees.pdf');
    Route::resource('trainees', TraineeController::class)->only(['index', 'show', 'edit', 'update']);

    // Quiz Management
    Route::resource('quizzes', QuizController::class);
    Route::get('/quizzes/{quiz}/results', [QuizController::class, 'results'])->name('quizzes.results');
    Route::get('/quizzes/{quiz}/results/pdf', [QuizController::class, 'downloadResultsPdf'])->name('quizzes.results.pdf');
    Route::post('/quizzes/{quiz}/toggle-status', [QuizController::class, 'toggleStatus'])->name('quizzes.toggle-status');
    Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::get('/quizzes/questions/template', [QuizController::class, 'downloadQuestionTemplate'])->name('quizzes.questions.template');
    Route::post('/quizzes/{quiz}/questions/import', [QuizController::class, 'importQuestions'])->name('quizzes.questions.import');
    Route::post('/quizzes/{quiz}/questions/{question}/toggle-status', [QuizController::class, 'toggleQuestionStatus'])->name('quizzes.questions.toggle-status');
    Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuizController::class, 'editQuestion'])->name('quizzes.questions.edit');
    Route::put('/quizzes/{quiz}/questions/{question}', [QuizController::class, 'updateQuestion'])->name('quizzes.questions.update');
    Route::delete('/quizzes/{quiz}/questions/{question}', [QuizController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

    // Assignment Management
    Route::resource('assignments', AssignmentController::class);
    Route::post('/assignments/{assignment}/toggle-status', [AssignmentController::class, 'toggleStatus'])->name('assignments.toggle-status');
    Route::get('/assignments/{assignment}/attachments/{attachment}/download', [AssignmentController::class, 'downloadAttachment'])->name('assignments.attachments.download');
    Route::get('/assignments/{assignment}/attachments/{attachment}/view', [AssignmentController::class, 'viewAttachment'])->name('assignments.attachments.view');
    Route::get('/assignments/{assignment}/files/{file}/download', [AssignmentController::class, 'downloadSubmissionFile'])->name('assignments.files.download');
    Route::get('/assignments/{assignment}/files/{file}/view', [AssignmentController::class, 'viewSubmissionFile'])->name('assignments.files.view');
    Route::get('/assignments/{assignment}/submissions/{submission}', [AssignmentController::class, 'showSubmission'])->name('assignments.submissions.show');
    Route::post('/assignments/{assignment}/submissions/{submission}/feedback', [AssignmentController::class, 'updateSubmissionFeedback'])->name('assignments.submissions.feedback');

    // Registration training options (for trainee signup)
    Route::resource('registration-trainings', RegistrationTrainingController::class)->except(['show']);

    // Activity monitoring
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('/online-users', [OnlineUserController::class, 'index'])->name('online-users.index');
    Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
});

// Trainer routes (email verification disabled)
Route::middleware(['auth'])->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('trainer.dashboard');
    })->name('dashboard');
});

