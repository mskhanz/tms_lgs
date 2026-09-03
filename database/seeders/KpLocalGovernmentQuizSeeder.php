<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class KpLocalGovernmentQuizSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/kp_lg_act_quiz.php');

        if (! File::exists($path)) {
            $this->command?->error('Quiz data file not found.');

            return;
        }

        $data = require $path;

        $quiz = Quiz::create($data['quiz'] + [
            'created_by' => \App\Models\User::query()->value('id'),
        ]);

        foreach ($data['questions'] as $index => $item) {
            $question = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'part' => $item['part'] ?? null,
                'question_text' => $item['text'],
                'marks' => 1,
                'sort_order' => $index + 1,
            ]);

            $letters = ['A', 'B', 'C', 'D'];
            foreach ($letters as $i => $letter) {
                if (! isset($item['options'][$letter])) {
                    continue;
                }

                $question->options()->create([
                    'option_text' => $item['options'][$letter],
                    'is_correct' => strtoupper($item['answer']) === $letter,
                    'sort_order' => $i,
                ]);
            }
        }

        $this->command?->info('KP Local Government quiz seeded with ' . count($data['questions']) . ' questions.');
    }
}
