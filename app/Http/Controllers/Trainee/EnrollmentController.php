<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');

        // URL "ongoing" = active enrollments (enrolled + in_progress).
        $filter = match ($status) {
            'ongoing', 'in_progress' => 'ongoing',
            'enrolled', 'completed', 'dropped', 'failed' => $status,
            default => null,
        };

        $query = $user->enrollments()
            ->with([
                'trainingBatch.trainingProgram.conductingOrganization',
                'trainingBatch.trainingProgram',
            ])
            ->latest('enrollment_date');

        if ($filter === 'ongoing') {
            $query->ongoing();
        } elseif ($filter) {
            $query->where('status', $filter);
        }

        $enrollments = $query->get();

        $counts = [
            'all' => $user->enrollments()->count(),
            'ongoing' => $user->enrollments()->ongoing()->count(),
            'enrolled' => $user->enrollments()->where('status', 'enrolled')->count(),
            'completed' => $user->enrollments()->where('status', 'completed')->count(),
        ];

        return view('trainee.enrollments.index', [
            'enrollments' => $enrollments,
            'status' => $filter,
            'counts' => $counts,
        ]);
    }
}
