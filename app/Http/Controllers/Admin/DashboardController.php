<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, TrainingProgram, TrainingBatch, TrainingEnrollment, Certificate, Organization};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $counts = Cache::remember('admin.dashboard.counts', 60, function () {
            return [
                'totalTrainees' => User::where('user_type', 'trainee')->count(),
                'totalPrograms' => TrainingProgram::count(),
                'activePrograms' => TrainingProgram::whereIn('status', ['approved', 'ongoing'])->count(),
                'completedPrograms' => TrainingProgram::where('status', 'completed')->count(),
                'totalEnrollments' => TrainingEnrollment::count(),
                'completedEnrollments' => TrainingEnrollment::where('status', 'completed')->count(),
                'ongoingEnrollments' => TrainingEnrollment::where('status', 'in_progress')->count(),
                'totalCertificates' => Certificate::where('status', 'issued')->count(),
                'activeBatches' => TrainingBatch::whereIn('status', ['scheduled', 'ongoing'])->count(),
                'upcomingBatches' => TrainingBatch::where('status', 'scheduled')->where('start_date', '>', now())->count(),
                'totalTrainers' => User::where('user_type', 'trainer')->count(),
                'pendingNominations' => \App\Models\TrainingNomination::where('status', 'pending')->count(),
                'pendingPrograms' => TrainingProgram::where('status', 'pending_approval')->count(),
            ];
        });

        $totalTrainees = $counts['totalTrainees'];
        $totalPrograms = $counts['totalPrograms'];
        $activePrograms = $counts['activePrograms'];
        $completedPrograms = $counts['completedPrograms'];
        $totalEnrollments = $counts['totalEnrollments'];
        $completedEnrollments = $counts['completedEnrollments'];
        $ongoingEnrollments = $counts['ongoingEnrollments'];
        $totalCertificates = $counts['totalCertificates'];
        $activeBatches = $counts['activeBatches'];
        $upcomingBatches = $counts['upcomingBatches'];
        $totalTrainers = $counts['totalTrainers'];

        $pendingNominations = 0;
        $pendingPrograms = 0;

        if ($user->hasAnyRole(['system_admin', 'director', 'deputy_director'])) {
            $pendingNominations = $counts['pendingNominations'];
            $pendingPrograms = $counts['pendingPrograms'];
        }
        
        // Recent enrollments
        $recentEnrollments = TrainingEnrollment::with([
            'trainee',
            'trainingBatch.trainingProgram',
            'enrolledBy'
        ])->latest()->limit(10)->get();
        
        // Upcoming batches list
        $upcomingBatchesList = TrainingBatch::with('trainingProgram')
            ->where('status', 'scheduled')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();
        
        // Training completion statistics by month (last 6 months)
        $completionStats = TrainingEnrollment::where('status', 'completed')
            ->where('completion_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(completion_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Training by category
        $trainingsByCategory = TrainingProgram::select('category', DB::raw('COUNT(*) as count'))
            ->groupBy('category')
            ->get();
        
        // Department-wise enrollment
        $enrollmentsByOrganization = Organization::withCount('traineeProfiles')
            ->orderByDesc('trainee_profiles_count')
            ->limit(10)
            ->get();
        
        // Pending approvals (for admins)
        $onlineUsersCount = null;
        $activitiesToday = 0;
        if ($user->hasRole(['system_admin', 'director'])) {
            $onlineUsersCount = app(\App\Services\LoginSessionTracker::class)->onlineCount();
            $activitiesToday = \App\Models\ActivityLog::query()->whereDate('created_at', today())->count();
        }
        
        return view('admin.dashboard', compact(
            'user',
            'totalTrainees',
            'totalPrograms',
            'activePrograms',
            'completedPrograms',
            'totalEnrollments',
            'completedEnrollments',
            'ongoingEnrollments',
            'totalCertificates',
            'activeBatches',
            'upcomingBatches',
            'totalTrainers',
            'recentEnrollments',
            'upcomingBatchesList',
            'completionStats',
            'trainingsByCategory',
            'enrollmentsByOrganization',
            'pendingNominations',
            'pendingPrograms',
            'onlineUsersCount',
            'activitiesToday'
        ));
    }
}
