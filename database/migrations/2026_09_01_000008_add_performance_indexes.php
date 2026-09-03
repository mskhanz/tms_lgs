<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndex('users', ['user_type']);
        $this->addIndex('users', ['is_active']);
        $this->addIndex('users', ['user_type', 'is_active']);
        $this->addIndex('users', ['deleted_at']);
        $this->addIndex('users', ['name']);
        $this->addIndex('users', ['registration_training_id']);

        $this->addIndex('role_user', ['user_id']);
        $this->addIndex('permission_role', ['role_id']);

        $this->addIndex('trainee_profiles', ['user_id']);
        $this->addIndex('trainee_profiles', ['organization_id']);
        $this->addIndex('trainee_profiles', ['district_id']);
        $this->addIndex('trainee_profiles', ['tehsil_id']);
        $this->addIndex('trainee_profiles', ['deleted_at']);

        $this->addIndex('trainee_qualifications', ['trainee_profile_id']);
        $this->addIndex('trainee_qualifications', ['degree_id']);
        $this->addIndex('trainee_qualifications', ['country_id']);
        $this->addIndex('trainee_qualifications', ['subject_id']);

        $this->addIndex('organizations', ['is_active']);
        $this->addIndex('organizations', ['district_id']);
        $this->addIndex('organizations', ['parent_id']);
        $this->addIndex('organizations', ['deleted_at']);
        $this->addIndex('organizations', ['is_active', 'district_id']);

        $this->addIndex('districts', ['is_active']);
        $this->addIndex('districts', ['name']);
        $this->addIndex('tehsils', ['district_id']);
        $this->addIndex('tehsils', ['is_active']);
        $this->addIndex('sections', ['organization_id']);
        $this->addIndex('degrees', ['is_active']);
        $this->addIndex('subjects', ['is_active']);
        $this->addIndex('countries', ['is_active']);
        $this->addIndex('designations', ['is_active']);
        $this->addIndex('designations', ['name']);

        $this->addIndex('training_programs', ['status']);
        $this->addIndex('training_programs', ['category']);
        $this->addIndex('training_programs', ['conducting_organization_id']);
        $this->addIndex('training_programs', ['created_by']);
        $this->addIndex('training_programs', ['deleted_at']);
        $this->addIndex('training_programs', ['status', 'deleted_at']);

        $this->addIndex('training_batches', ['training_program_id']);
        $this->addIndex('training_batches', ['status']);
        $this->addIndex('training_batches', ['start_date']);
        $this->addIndex('training_batches', ['coordinator_id']);
        $this->addIndex('training_batches', ['deleted_at']);
        $this->addIndex('training_batches', ['status', 'start_date']);

        $this->addIndex('training_enrollments', ['trainee_id']);
        $this->addIndex('training_enrollments', ['training_batch_id']);
        $this->addIndex('training_enrollments', ['status']);
        $this->addIndex('training_enrollments', ['enrolled_by']);
        $this->addIndex('training_enrollments', ['nomination_id']);
        $this->addIndex('training_enrollments', ['deleted_at']);
        $this->addIndex('training_enrollments', ['trainee_id', 'training_batch_id']);
        $this->addIndex('training_enrollments', ['trainee_id', 'status']);
        $this->addIndex('training_enrollments', ['status', 'completion_date']);

        $this->addIndex('training_nominations', ['trainee_id']);
        $this->addIndex('training_nominations', ['training_batch_id']);
        $this->addIndex('training_nominations', ['status']);
        $this->addIndex('training_nominations', ['organization_id']);
        $this->addIndex('training_nominations', ['deleted_at']);

        $this->addIndex('training_batch_trainers', ['training_batch_id']);
        $this->addIndex('training_batch_trainers', ['trainer_id']);

        $this->addIndex('training_sessions', ['training_batch_id']);
        $this->addIndex('training_sessions', ['trainer_id']);

        $this->addIndex('attendance_records', ['training_session_id']);
        $this->addIndex('attendance_records', ['enrollment_id']);
        $this->addIndex('attendance_records', ['trainee_id']);

        $this->addIndex('assessments', ['training_program_id']);
        $this->addIndex('assessment_results', ['assessment_id']);
        $this->addIndex('assessment_results', ['enrollment_id']);
        $this->addIndex('assessment_results', ['trainee_id']);

        $this->addIndex('certificates', ['trainee_id']);
        $this->addIndex('certificates', ['training_batch_id']);
        $this->addIndex('certificates', ['enrollment_id']);
        $this->addIndex('certificates', ['status']);

        $this->addIndex('trainers', ['user_id']);
        $this->addIndex('trainers', ['status']);
        $this->addIndex('trainers', ['deleted_at']);

        $this->addIndex('quizzes', ['is_active']);
        $this->addIndex('quizzes', ['created_by']);
        $this->addIndex('quizzes', ['deleted_at']);
        $this->addIndex('quizzes', ['assign_to']);
        $this->addIndex('quiz_questions', ['quiz_id']);
        $this->addIndex('quiz_questions', ['quiz_id', 'sort_order']);
        $this->addIndex('quiz_options', ['question_id']);
        $this->addIndex('quiz_attempts', ['user_id']);
        $this->addIndex('quiz_attempts', ['status']);
        $this->addIndex('quiz_attempts', ['quiz_id', 'user_id', 'status']);
        $this->addIndex('quiz_attempt_answers', ['attempt_id']);
        $this->addIndex('quiz_attempt_answers', ['question_id']);

        $this->addIndex('activity_logs', ['log_name']);
        $this->addIndex('activity_logs', ['created_at']);
        $this->addIndex('activity_logs', ['log_name', 'created_at']);

        $this->addIndex('notifications', ['user_id', 'created_at']);
        $this->addIndex('login_sessions', ['logged_in_at']);
        $this->addIndex('login_sessions', ['logged_out_at', 'last_activity_at']);

        $this->addIndex('registration_trainings', ['is_active']);
        $this->addIndex('registration_trainings', ['sort_order']);
    }

    public function down(): void
    {
        // Indexes are left in place; dropping them individually is not required for rollback safety.
    }

    private function addIndex(string $table, array $columns): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        $name = $table.'_'.implode('_', $columns).'_index';
        if (strlen($name) > 64) {
            $name = substr(hash('sha1', $name), 0, 16).'_idx';
        }

        if ($this->indexExists($table, $name) || $this->coversColumns($table, $columns)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
            $blueprint->index($columns, $name);
        });
    }

    private function indexExists(string $table, string $name): bool
    {
        foreach (DB::select('SHOW INDEX FROM `'.$table.'`') as $row) {
            if ($row->Key_name === $name) {
                return true;
            }
        }

        return false;
    }

    private function coversColumns(string $table, array $columns): bool
    {
        $rows = DB::select('SHOW INDEX FROM `'.$table.'`');
        $grouped = [];

        foreach ($rows as $row) {
            $grouped[$row->Key_name][(int) $row->Seq_in_index] = $row->Column_name;
        }

        foreach ($grouped as $ordered) {
            ksort($ordered);
            if (array_values($ordered) === $columns) {
                return true;
            }
        }

        return false;
    }
};
