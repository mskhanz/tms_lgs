<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{TraineeProfile, TrainingEnrollment, User};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TraineeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('user_type', 'trainee')->with('traineeProfile.organization');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('traineeProfile', function($q) use ($search) {
                      $q->where('cnic_no', 'like', "%{$search}%")
                        ->orWhere('contact_no', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        
        $trainees = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.trainees.index', compact('trainees'));
    }
    
    public function show($id)
    {
        $trainee = $this->loadTrainee($id);
        $profile = $trainee->traineeProfile;
        $enrollmentSummaries = $this->buildEnrollmentSummaries($trainee->enrollments);
        $attendanceOverview = $this->buildAttendanceOverview($enrollmentSummaries);

        return view('admin.trainees.show', compact('trainee', 'profile', 'enrollmentSummaries', 'attendanceOverview'));
    }

    public function downloadPdf($id)
    {
        $trainee = $this->loadTrainee($id);
        $profile = $trainee->traineeProfile;
        $enrollmentSummaries = $this->buildEnrollmentSummaries($trainee->enrollments);
        $attendanceOverview = $this->buildAttendanceOverview($enrollmentSummaries);

        $pdf = Pdf::loadView('admin.trainees.pdf', compact('trainee', 'profile', 'enrollmentSummaries', 'attendanceOverview'))
            ->setPaper('a4', 'portrait');

        $name = $profile?->emp_name ?: $trainee->name;
        $filename = 'trainee-dossier-'.Str::slug($name).'.pdf';

        return $pdf->download($filename);
    }
    
    public function edit($id)
    {
        $trainee = User::where('user_type', 'trainee')->with('traineeProfile', 'enrollments')->findOrFail($id);
        
        return view('admin.trainees.edit', compact('trainee'));
    }
    
    public function update(Request $request, $id)
    {
        $trainee = User::where('user_type', 'trainee')->with('traineeProfile')->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $trainee->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'father_name' => 'nullable|string|max:255',
            'cnic_no' => 'nullable|string|max:15',
            'gender' => 'nullable|in:male,female,other',
            'dob' => 'nullable|date',
            'contact_no' => 'nullable|string|max:20',
            'alt_contact_no' => 'nullable|string|max:20',
            'whatsapp_no' => 'nullable|string|max:20',
            'designation' => 'nullable|string|max:255',
            'bps' => 'nullable|integer|min:1|max:22',
            'is_active' => 'required|boolean'
        ]);
        
        // Update user data
        $trainee->name = $validated['name'];
        $trainee->email = $validated['email'];
        $trainee->is_active = $validated['is_active'];
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($trainee->photo && file_exists(public_path('user_photos/' . $trainee->photo))) {
                unlink(public_path('user_photos/' . $trainee->photo));
            }
            
            $file = $request->file('photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('user_photos'), $filename);
            $trainee->photo = $filename;
        }
        
        $trainee->save();
        
        // Update or create trainee profile
        $profileData = [
            'father_name' => $validated['father_name'] ?? null,
            'cnic_no' => $validated['cnic_no'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'contact_no' => $validated['contact_no'] ?? null,
            'alt_contact_no' => $validated['alt_contact_no'] ?? null,
            'whatsapp_no' => $validated['whatsapp_no'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'bps' => $validated['bps'] ?? null,
        ];
        
        if ($trainee->traineeProfile) {
            $trainee->traineeProfile->update($profileData);
        } else {
            TraineeProfile::create(array_merge($profileData, ['user_id' => $trainee->id]));
        }
        
        return redirect()->route('admin.trainees.show', $trainee->id)
            ->with('success', 'Trainee profile updated successfully!');
    }

    private function loadTrainee($id): User
    {
        return User::where('user_type', 'trainee')
            ->with([
                'traineeProfile.organization',
                'traineeProfile.section',
                'traineeProfile.district',
                'traineeProfile.tehsil',
                'traineeProfile.serviceStatus',
                'traineeProfile.qualifications.degree',
                'traineeProfile.qualifications.subject',
                'traineeProfile.qualifications.country',
                'traineeProfile.completedBy',
                'traineeProfile.updatedBy',
                'enrollments' => fn ($query) => $query->latest('enrollment_date'),
                'enrollments.trainingBatch.trainingProgram',
                'enrollments.trainingBatch.sessions' => fn ($query) => $query
                    ->orderBy('session_date')
                    ->orderBy('start_time'),
                'enrollments.trainingBatch.sessions.sessionType',
                'enrollments.attendanceRecords.trainingSession',
                'enrollments.enrolledBy',
                'roles',
            ])
            ->findOrFail($id);
    }

    private function buildEnrollmentSummaries(Collection $enrollments): Collection
    {
        return $enrollments->map(function (TrainingEnrollment $enrollment) {
            $batch = $enrollment->trainingBatch;
            $program = $batch?->trainingProgram;
            $records = $enrollment->attendanceRecords->keyBy('training_session_id');

            $sessionRows = ($batch?->sessions ?? collect())->map(function ($session) use ($records) {
                $record = $records->get($session->id);

                return (object) [
                    'session' => $session,
                    'record' => $record,
                    'status' => $record?->status ?? 'not_marked',
                ];
            });

            $statusCounts = [
                'present' => $sessionRows->where('status', 'present')->count(),
                'absent' => $sessionRows->where('status', 'absent')->count(),
                'late' => $sessionRows->where('status', 'late')->count(),
                'excused' => $sessionRows->where('status', 'excused')->count(),
                'not_marked' => $sessionRows->where('status', 'not_marked')->count(),
            ];

            return (object) [
                'enrollment' => $enrollment,
                'batch' => $batch,
                'program' => $program,
                'sessionRows' => $sessionRows,
                'statusCounts' => $statusCounts,
                'showAttendance' => $batch
                    && ($batch->isAttendanceEnabled() || $enrollment->attendanceRecords->isNotEmpty()),
            ];
        });
    }

    private function buildAttendanceOverview(Collection $enrollmentSummaries): array
    {
        $totalSessions = 0;
        $presentCount = 0;

        foreach ($enrollmentSummaries as $summary) {
            if (! $summary->showAttendance) {
                continue;
            }

            foreach ($summary->sessionRows as $row) {
                if ($row->status === 'not_marked') {
                    continue;
                }

                $totalSessions++;
                if (in_array($row->status, ['present', 'late'], true)) {
                    $presentCount++;
                }
            }
        }

        return [
            'totalSessions' => $totalSessions,
            'presentCount' => $presentCount,
            'overallPercentage' => $totalSessions > 0
                ? round(($presentCount / $totalSessions) * 100, 1)
                : 0,
        ];
    }
}
