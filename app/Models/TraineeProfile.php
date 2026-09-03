<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TraineeProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'cnic_no', 'emp_name', 'father_name', 'gender', 'personal_no',
        'trainee_type', 'dob', 'domicile', 'cadre', 'service_status_id',
        'emp_email', 'emp_whatsapp_no', 'contact_no', 'date_of_initial_appointment',
        'permanent_address', 'current_address', 'remarks', 'file_picture',
        'district_id', 'tehsil_id', 'organization_id', 'section_id',
        'designation', 'bps', 'status', 'from_date',
        'completed_by', 'completed_at', 'updated_by'
    ];

    protected $casts = [
        'dob' => 'date',
        'date_of_initial_appointment' => 'date',
        'from_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceStatus()
    {
        return $this->belongsTo(ServiceStatus::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function qualifications()
    {
        return $this->hasMany(TraineeQualification::class);
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function ageInYears(): ?int
    {
        return $this->dob ? $this->dob->age : null;
    }

    public function ageLabel(): string
    {
        $years = $this->ageInYears();

        if ($years === null) {
            return 'N/A';
        }

        return $years.' '.str('year')->plural($years);
    }

    public function serviceLengthLabel(): string
    {
        if (! $this->date_of_initial_appointment) {
            return 'N/A';
        }

        $start = $this->date_of_initial_appointment->copy()->startOfDay();
        $end = now()->startOfDay();

        if ($start->greaterThan($end)) {
            return 'Not started';
        }

        $diff = $start->diff($end);
        $parts = [];

        if ($diff->y > 0) {
            $parts[] = $diff->y.' '.str('year')->plural($diff->y);
        }
        if ($diff->m > 0) {
            $parts[] = $diff->m.' '.str('month')->plural($diff->m);
        }
        if ($parts === [] && $diff->d > 0) {
            $parts[] = $diff->d.' '.str('day')->plural($diff->d);
        }

        return $parts === [] ? 'Less than a day' : implode(' ', $parts);
    }

    public function photoUrl(): ?string
    {
        if ($this->file_picture && file_exists(public_path('trainee_pictures/'.$this->file_picture))) {
            return asset('trainee_pictures/'.$this->file_picture);
        }

        if ($this->relationLoaded('user') === false) {
            $this->load('user');
        }

        if ($this->user && $this->user->photo && file_exists(public_path('user_photos/'.$this->user->photo))) {
            return asset('user_photos/'.$this->user->photo);
        }

        return null;
    }

    public function photoDataUri(): ?string
    {
        $path = null;

        if ($this->file_picture && file_exists(public_path('trainee_pictures/'.$this->file_picture))) {
            $path = public_path('trainee_pictures/'.$this->file_picture);
        } elseif ($this->user && $this->user->photo && file_exists(public_path('user_photos/'.$this->user->photo))) {
            $path = public_path('user_photos/'.$this->user->photo);
        }

        if (! $path) {
            return null;
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';

        return 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($path));
    }
}
