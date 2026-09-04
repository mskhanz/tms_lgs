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

        $allowedStatuses = ['enrolled', 'in_progress', 'completed', 'dropped', 'failed'];
        if ($status && ! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        $query = $user->enrollments()
            ->with([
                'trainingBatch.trainingProgram.conductingOrganization',
                'trainingBatch.trainingProgram',
            ])
            ->latest('enrollment_date');

        if ($status) {
            $query->where('status', $status);
        }

        $enrollments = $query->get();

        $counts = [
            'all' => $user->enrollments()->count(),
            'in_progress' => $user->enrollments()->where('status', 'in_progress')->count(),
            'enrolled' => $user->enrollments()->where('status', 'enrolled')->count(),
            'completed' => $user->enrollments()->where('status', 'completed')->count(),
        ];

        return view('trainee.enrollments.index', compact('enrollments', 'status', 'counts'));
    }
}
