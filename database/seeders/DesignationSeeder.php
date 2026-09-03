<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            'Assistant',
            'Senior Assistant',
            'Superintendent',
            'Assistant Director',
            'Deputy Director',
            'Director',
            'Additional Director',
            'Joint Director',
            'Officer',
            'Senior Officer',
            'Chief Officer',
            'Manager',
            'Senior Manager',
            'Engineer',
            'Senior Engineer',
            'Chief Engineer',
            'Accountant',
            'Senior Accountant',
            'Auditor',
            'Clerk',
        ];

        foreach ($designations as $designation) {
            Designation::create([
                'name' => $designation,
                'is_active' => true,
            ]);
        }
    }
}
