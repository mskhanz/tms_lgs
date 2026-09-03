<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\TraineeProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // System Admin
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $admin->roles()->attach(Role::where('name', 'system_admin')->first()->id);

        // Director
        $director = User::create([
            'name' => 'Director LGS-TMS',
            'email' => 'director@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $director->roles()->attach(Role::where('name', 'director')->first()->id);

        // Deputy Director
        $deputyDirector = User::create([
            'name' => 'Deputy Director LGS-TMS',
            'email' => 'deputydirector@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $deputyDirector->roles()->attach(Role::where('name', 'deputy_director')->first()->id);

        // Training Officer
        $trainingOfficer = User::create([
            'name' => 'Training Officer',
            'email' => 'training.officer@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $trainingOfficer->roles()->attach(Role::where('name', 'training_officer')->first()->id);

        // Trainer
        $trainer = User::create([
            'name' => 'Trainer User',
            'email' => 'trainer@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'trainer',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $trainer->roles()->attach(Role::where('name', 'trainer')->first()->id);

        // Sample Trainee
        $trainee = User::create([
            'name' => 'John Doe',
            'email' => 'trainee@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'trainee',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => false,
        ]);
        $trainee->roles()->attach(Role::where('name', 'trainee')->first()->id);

        // Sample Trainee with Profile
        $trainee2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'trainee2@lgstms.kp.gov.pk',
            'password' => Hash::make('password'),
            'user_type' => 'trainee',
            'email_verified_at' => now(),
            'is_active' => true,
            'profile_completed' => true,
        ]);
        $trainee2->roles()->attach(Role::where('name', 'trainee')->first()->id);

        // Create sample trainee profile
        TraineeProfile::create([
            'user_id' => $trainee2->id,
            'cnic_no' => '1234567890123',
            'emp_name' => 'Jane Smith',
            'father_name' => 'Robert Smith',
            'gender' => 'female',
            'personal_no' => 'EMP-001',
            'trainee_type' => 'regular',
            'dob' => '1990-01-15',
            'domicile' => 'Peshawar',
            'cadre' => 'BPS-17',
            'service_status_id' => 1,
            'emp_email' => 'trainee2@lgstms.kp.gov.pk',
            'contact_no' => '0300-1234567',
            'date_of_initial_appointment' => '2015-06-01',
            'permanent_address' => '123 Main Street, Peshawar',
            'current_address' => '123 Main Street, Peshawar',
            'district_id' => 1,
            'organization_id' => 2,
            'designation' => 'Assistant',
            'bps' => 17,
            'completed_by' => $trainer->id,
            'completed_at' => now(),
        ]);
    }
}
