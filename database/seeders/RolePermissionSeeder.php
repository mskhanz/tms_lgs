<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roles = [
            ['name' => 'system_admin', 'display_name' => 'System Administrator', 'description' => 'Full system access'],
            ['name' => 'director', 'display_name' => 'Director', 'description' => 'Director level access'],
            ['name' => 'deputy_director', 'display_name' => 'Deputy Director', 'description' => 'Deputy Director level access'],
            ['name' => 'training_officer', 'display_name' => 'Training Officer', 'description' => 'Training Officer access'],
            ['name' => 'department_admin', 'display_name' => 'Department Admin', 'description' => 'Department level administration'],
            ['name' => 'institute_admin', 'display_name' => 'Institute Admin', 'description' => 'Institute level administration'],
            ['name' => 'trainer', 'display_name' => 'Trainer', 'description' => 'Trainer access'],
            ['name' => 'trainee', 'display_name' => 'Trainee', 'description' => 'Trainee access'],
            ['name' => 'auditor', 'display_name' => 'Auditor', 'description' => 'Read-only audit access'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Permissions
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users'],
            ['name' => 'users.create', 'display_name' => 'Create Users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users'],
            
            // Trainee Management
            ['name' => 'trainees.view', 'display_name' => 'View Trainees'],
            ['name' => 'trainees.create', 'display_name' => 'Create Trainees'],
            ['name' => 'trainees.edit', 'display_name' => 'Edit Trainees'],
            ['name' => 'trainees.profile.complete', 'display_name' => 'Complete Trainee Profile'],
            
            // Training Programs
            ['name' => 'programs.view', 'display_name' => 'View Programs'],
            ['name' => 'programs.create', 'display_name' => 'Create Programs'],
            ['name' => 'programs.edit', 'display_name' => 'Edit Programs'],
            ['name' => 'programs.delete', 'display_name' => 'Delete Programs'],
            ['name' => 'programs.approve', 'display_name' => 'Approve Programs'],
            
            // Enrollments
            ['name' => 'enrollments.view', 'display_name' => 'View Enrollments'],
            ['name' => 'enrollments.create', 'display_name' => 'Create Enrollments'],
            ['name' => 'enrollments.edit', 'display_name' => 'Edit Enrollments'],
            ['name' => 'enrollments.delete', 'display_name' => 'Delete Enrollments'],
            
            // Nominations
            ['name' => 'nominations.view', 'display_name' => 'View Nominations'],
            ['name' => 'nominations.create', 'display_name' => 'Create Nominations'],
            ['name' => 'nominations.approve', 'display_name' => 'Approve Nominations'],
            
            // Attendance
            ['name' => 'attendance.view', 'display_name' => 'View Attendance'],
            ['name' => 'attendance.mark', 'display_name' => 'Mark Attendance'],
            
            // Assessments
            ['name' => 'assessments.view', 'display_name' => 'View Assessments'],
            ['name' => 'assessments.create', 'display_name' => 'Create Assessments'],
            ['name' => 'assessments.evaluate', 'display_name' => 'Evaluate Assessments'],
            ['name' => 'assessments.approve', 'display_name' => 'Approve Assessment Results'],
            
            // Certificates
            ['name' => 'certificates.view', 'display_name' => 'View Certificates'],
            ['name' => 'certificates.issue', 'display_name' => 'Issue Certificates'],
            ['name' => 'certificates.approve', 'display_name' => 'Approve Certificates'],
            
            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports'],
            
            // Organizations
            ['name' => 'organizations.view', 'display_name' => 'View Organizations'],
            ['name' => 'organizations.create', 'display_name' => 'Create Organizations'],
            ['name' => 'organizations.edit', 'display_name' => 'Edit Organizations'],
            
            // Trainers
            ['name' => 'trainers.view', 'display_name' => 'View Trainers'],
            ['name' => 'trainers.create', 'display_name' => 'Create Trainers'],
            ['name' => 'trainers.approve', 'display_name' => 'Approve Trainers'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Assign Permissions to Roles
        $systemAdmin = Role::where('name', 'system_admin')->first();
        $systemAdmin->permissions()->attach(Permission::all());

        $director = Role::where('name', 'director')->first();
        $director->permissions()->attach(Permission::whereIn('name', [
            'users.view', 'trainees.view', 'programs.view', 'programs.approve',
            'enrollments.view', 'nominations.view', 'nominations.approve',
            'attendance.view', 'assessments.view', 'assessments.approve',
            'certificates.view', 'certificates.approve', 'reports.view', 'reports.export',
            'organizations.view', 'trainers.view', 'trainers.approve'
        ])->pluck('id'));

        $trainingOfficer = Role::where('name', 'training_officer')->first();
        $trainingOfficer->permissions()->attach(Permission::whereIn('name', [
            'trainees.view', 'trainees.edit', 'trainees.profile.complete',
            'programs.view', 'programs.create', 'programs.edit',
            'enrollments.view', 'enrollments.create', 'enrollments.edit',
            'nominations.view', 'nominations.create',
            'attendance.view', 'attendance.mark',
            'assessments.view', 'certificates.view', 'certificates.issue',
            'reports.view', 'trainers.view'
        ])->pluck('id'));

        $trainer = Role::where('name', 'trainer')->first();
        $trainer->permissions()->attach(Permission::whereIn('name', [
            'trainees.view', 'trainees.profile.complete',
            'programs.view', 'attendance.view', 'attendance.mark',
            'assessments.view', 'assessments.evaluate'
        ])->pluck('id'));

        $trainee = Role::where('name', 'trainee')->first();
        $trainee->permissions()->attach(Permission::whereIn('name', [
            'trainees.edit', 'programs.view', 'enrollments.view',
            'attendance.view', 'assessments.view', 'certificates.view'
        ])->pluck('id'));
    }
}
