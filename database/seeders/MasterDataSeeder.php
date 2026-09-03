<?php

namespace Database\Seeders;

use App\Models\{Country, District, Tehsil, Organization, Section, ServiceStatus, Degree, Subject};
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Countries
        $countries = [
            ['name' => 'Pakistan', 'code' => 'PK'],
            ['name' => 'United States', 'code' => 'US'],
            ['name' => 'United Kingdom', 'code' => 'UK'],
            ['name' => 'China', 'code' => 'CN'],
            ['name' => 'India', 'code' => 'IN'],
        ];
        foreach ($countries as $country) {
            Country::create($country);
        }

        // Districts of KP
        $districts = [
            ['name' => 'Peshawar', 'code' => 'PSH'],
            ['name' => 'Mardan', 'code' => 'MRD'],
            ['name' => 'Abbottabad', 'code' => 'ABT'],
            ['name' => 'Swat', 'code' => 'SWT'],
            ['name' => 'Dera Ismail Khan', 'code' => 'DIK'],
            ['name' => 'Kohat', 'code' => 'KHT'],
            ['name' => 'Bannu', 'code' => 'BNU'],
            ['name' => 'Mansehra', 'code' => 'MNS'],
            ['name' => 'Charsadda', 'code' => 'CHR'],
            ['name' => 'Nowshera', 'code' => 'NSW'],
        ];
        foreach ($districts as $district) {
            District::create($district);
        }

        // Tehsils (Sample for Peshawar)
        $peshawar = District::where('name', 'Peshawar')->first();
        $tehsils = [
            ['district_id' => $peshawar->id, 'name' => 'Peshawar City', 'code' => 'PSH-C'],
            ['district_id' => $peshawar->id, 'name' => 'Peshawar Saddar', 'code' => 'PSH-S'],
            ['district_id' => $peshawar->id, 'name' => 'Hassan Khel', 'code' => 'PSH-HK'],
        ];
        foreach ($tehsils as $tehsil) {
            Tehsil::create($tehsil);
        }

        // Service Statuses
        $serviceStatuses = [
            ['name' => 'Regular', 'description' => 'Regular Service'],
            ['name' => 'Contract', 'description' => 'Contract Based'],
            ['name' => 'Daily Wage', 'description' => 'Daily Wage Worker'],
            ['name' => 'Deputation', 'description' => 'On Deputation'],
            ['name' => 'Retired', 'description' => 'Retired'],
        ];
        foreach ($serviceStatuses as $status) {
            ServiceStatus::create($status);
        }

        // Degrees
        $degrees = [
            ['name' => 'Matric', 'level' => 'matric'],
            ['name' => 'Intermediate', 'level' => 'intermediate'],
            ['name' => 'Bachelor', 'level' => 'bachelors'],
            ['name' => 'Master', 'level' => 'masters'],
            ['name' => 'M.Phil', 'level' => 'mphil'],
            ['name' => 'PhD', 'level' => 'phd'],
            ['name' => 'Diploma', 'level' => 'diploma'],
        ];
        foreach ($degrees as $degree) {
            Degree::create($degree);
        }

        // Subjects
        $subjects = [
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Business Administration', 'code' => 'BBA'],
            ['name' => 'Public Administration', 'code' => 'PA'],
            ['name' => 'Economics', 'code' => 'ECO'],
            ['name' => 'Law', 'code' => 'LAW'],
            ['name' => 'Engineering', 'code' => 'ENG'],
            ['name' => 'Management Sciences', 'code' => 'MS'],
        ];
        foreach ($subjects as $subject) {
            Subject::create($subject);
        }

        // Organizations
        $organizations = [
            [
                'name' => 'Local Government Department',
                'code' => 'LGD',
                'type' => 'department',
                'parent_id' => null,
                'address' => 'Civil Secretariat, Peshawar',
                'contact_number' => '091-9210101',
                'email' => 'lgd@kp.gov.pk',
            ],
            [
                'name' => 'Local Council Board',
                'code' => 'LCB',
                'type' => 'attached_department',
                'parent_id' => null, // Will be updated
                'address' => 'Peshawar',
                'contact_number' => '091-9210102',
                'email' => 'lcb@kp.gov.pk',
            ],
        ];
        
        $lgd = Organization::create($organizations[0]);
        $organizations[1]['parent_id'] = $lgd->id;
        $lcb = Organization::create($organizations[1]);

        // Sections
        $sections = [
            ['organization_id' => $lcb->id, 'name' => 'Training Section', 'code' => 'TRN'],
            ['organization_id' => $lcb->id, 'name' => 'Administration Section', 'code' => 'ADM'],
            ['organization_id' => $lcb->id, 'name' => 'Finance Section', 'code' => 'FIN'],
        ];
        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
