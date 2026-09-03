<?php

namespace Database\Seeders;

use App\Models\RegistrationTraining;
use Illuminate\Database\Seeder;

class RegistrationTrainingSeeder extends Seeder
{
    public function run(): void
    {
        $trainings = [
            [
                'title' => 'THREE (03) MONTHS MANDATORY PRE-PROMOTION/ MID CAREER TRAINING',
                'description' => 'Mandatory pre-promotion and mid-career training for local government officers.',
                'sort_order' => 1,
            ],
            [
                'title' => 'TWO (02) WEEKS MANDATORY INDUCTION TRAINING',
                'description' => 'Induction training for newly appointed local government staff.',
                'sort_order' => 2,
            ],
            [
                'title' => 'ONE (01) WEEK REFRESHER TRAINING',
                'description' => 'Short refresher course for serving officers.',
                'sort_order' => 3,
            ],
        ];

        foreach ($trainings as $training) {
            RegistrationTraining::updateOrCreate(
                ['title' => $training['title']],
                $training + ['is_active' => true]
            );
        }
    }
}
