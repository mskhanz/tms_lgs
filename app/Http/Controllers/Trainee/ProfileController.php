<?php

namespace App\Http\Controllers\Trainee;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\TraineeProfile;
use App\Support\LookupCache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $this->loadProfile($user);
        $enrollments = $user->enrollments()
            ->with(['trainingBatch.trainingProgram'])
            ->latest('enrollment_date')
            ->get();

        return view('trainee.profile.show', compact('user', 'profile', 'enrollments'));
    }

    public function downloadPdf()
    {
        $user = Auth::user();
        $profile = $this->loadProfile($user);

        if (! $profile) {
            return redirect()->route('trainee.profile.show')->with('error', 'Profile data is not available to download.');
        }

        $pdf = Pdf::loadView('trainee.profile.pdf', compact('user', 'profile'))
            ->setPaper('a4', 'portrait');

        $filename = 'trainee-profile-'.Str::slug($profile->emp_name ?: $user->name).'.pdf';

        return $pdf->download($filename);
    }
    
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->traineeProfile;

        if ($profile) {
            $profile->load('qualifications');
        }

        $districts = LookupCache::districts();
        $organizations = LookupCache::organizations();
        $designations = LookupCache::designations();
        $degrees = LookupCache::degrees();
        $subjects = LookupCache::subjects();
        $countries = LookupCache::countries();

        return view('trainee.profile.edit', compact(
            'user', 'profile', 'districts', 'organizations', 'designations',
            'degrees', 'subjects', 'countries'
        ));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->traineeProfile;
        
        $validated = $request->validate([
            // Personal Information
            'emp_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'cnic_no' => 'required|string|regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/|unique:trainee_profiles,cnic_no,' . ($profile ? $profile->id : ''),
            'personal_no' => 'nullable|string|max:50',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'domicile' => 'nullable|string|max:100',
            'trainee_type' => 'nullable|in:PUGF,NON-PUGF',
            'cadre' => 'nullable|in:Admin,Engineering,Finance,Other',
            
            // Contact Information
            'emp_email' => 'required|email|max:255',
            'contact_no' => 'required|string|regex:/^03[0-9]{2}-[0-9]{7}$/',
            'permanent_address' => 'nullable|string',
            'current_address' => 'nullable|string',
            
            // Employment Details
            'organization_id' => [
                'required',
                'exists:organizations,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                    $organization = Organization::find($value);
                    if (! $organization) {
                        return;
                    }
                    if ($organization->district_id && (int) $organization->district_id !== (int) $request->district_id) {
                        $fail('The selected organization does not belong to the selected district.');
                    }
                },
            ],
            'designation' => 'required|string|max:255',
            'bps' => 'required|integer|min:1|max:22',
            'date_of_initial_appointment' => 'nullable|date',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'posting_status' => 'nullable|in:0,1',
            'district_id' => 'required|exists:districts,id',
            'tehsil_id' => 'nullable|exists:tehsils,id',
            
            // Additional
            'remarks' => 'nullable|string',
            'file_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',

            'qualifications' => 'nullable|array',
            'qualifications.*.id' => 'nullable|integer',
            'qualifications.*.degree_id' => 'nullable|exists:degrees,id',
            'qualifications.*.subject_id' => 'nullable|exists:subjects,id',
            'qualifications.*.institute' => 'nullable|string|max:255',
            'qualifications.*.country_id' => 'nullable|exists:countries,id',
            'qualifications.*.passing_year' => 'nullable|integer|min:1950|max:'.(now()->year + 1),
            'qualifications.*.percentage_marks' => 'nullable|string|max:20',
        ], [], [
            'qualifications.*.degree_id' => 'degree',
            'qualifications.*.subject_id' => 'subject',
            'qualifications.*.institute' => 'institute',
            'qualifications.*.country_id' => 'country',
            'qualifications.*.passing_year' => 'passing year',
            'qualifications.*.percentage_marks' => 'marks / percentage',
        ]);
        
        // Handle file upload
        if ($request->hasFile('file_picture')) {
            // Delete old picture if exists
            if ($profile && $profile->file_picture && file_exists(public_path('trainee_pictures/' . $profile->file_picture))) {
                unlink(public_path('trainee_pictures/' . $profile->file_picture));
            }
            
            $file = $request->file('file_picture');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('trainee_pictures'), $filename);
            $validated['file_picture'] = $filename;
        }
        
        $validated['updated_by'] = $user->id;

        if (array_key_exists('posting_status', $validated)) {
            $validated['status'] = $validated['posting_status'];
            unset($validated['posting_status']);
        }
        unset($validated['to_date']);

        $qualificationRows = $validated['qualifications'] ?? [];
        unset($validated['qualifications']);

        // Update or create profile
        if ($profile) {
            $profile->update($validated);
        } else {
            $validated['user_id'] = $user->id;
            $profile = TraineeProfile::create($validated);
        }

        $this->syncQualifications($profile, $qualificationRows);

        activity()
            ->useLog('profile')
            ->performedOn($profile)
            ->causedBy($user)
            ->log('Updated profile');
        
        // Mark profile as completed if all required fields are filled
        if (!$user->profile_completed) {
            $requiredFields = ['emp_name', 'father_name', 'cnic_no', 'gender', 'dob', 
                             'contact_no', 'designation', 'bps', 'organization_id', 'district_id'];
            $allFilled = true;
            
            foreach ($requiredFields as $field) {
                if (empty($validated[$field])) {
                    $allFilled = false;
                    break;
                }
            }
            
            if ($allFilled) {
                $user->update(['profile_completed' => true]);
                if ($profile) {
                    $profile->update([
                        'completed_at' => now(),
                        'completed_by' => $user->id
                    ]);
                }
            }
        }
        
        return redirect()->route('trainee.profile.show')->with('success', 'Profile updated successfully!');
    }
    
    public function checkCnic(Request $request)
    {
        $cnic = $request->query('cnic');
        $userId = Auth::id();
        
        // Check if CNIC exists for any user except the current user
        $exists = TraineeProfile::where('cnic_no', $cnic)
            ->where('user_id', '!=', $userId)
            ->exists();
        
        return response()->json(['exists' => $exists]);
    }

    private function loadProfile($user): ?TraineeProfile
    {
        $profile = $user->traineeProfile;

        if (! $profile) {
            return null;
        }

        return $profile->load([
            'user',
            'organization',
            'district',
            'tehsil',
            'section',
            'serviceStatus',
            'qualifications.degree',
            'qualifications.subject',
            'qualifications.country',
            'completedBy',
            'updatedBy',
        ]);
    }

    private function syncQualifications(TraineeProfile $profile, array $rows): void
    {
        $keepIds = [];

        foreach ($rows as $row) {
            if (empty($row['degree_id']) || empty(trim((string) ($row['institute'] ?? ''))) || empty($row['country_id']) || empty($row['passing_year'])) {
                continue;
            }

            $data = [
                'degree_id' => $row['degree_id'],
                'subject_id' => ! empty($row['subject_id']) ? $row['subject_id'] : null,
                'institute' => trim((string) ($row['institute'] ?? '')),
                'country_id' => $row['country_id'],
                'passing_year' => $row['passing_year'],
                'percentage_marks' => (isset($row['percentage_marks']) && $row['percentage_marks'] !== '')
                    ? $row['percentage_marks']
                    : null,
            ];

            $qualification = ! empty($row['id'])
                ? $profile->qualifications()->where('id', $row['id'])->first()
                : null;

            if ($qualification) {
                $qualification->update($data);
            } else {
                $qualification = $profile->qualifications()->create($data);
            }

            $keepIds[] = $qualification->id;
        }

        $query = $profile->qualifications();
        if ($keepIds !== []) {
            $query->whereNotIn('id', $keepIds);
        }
        $query->delete();
    }
}
