<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('assign_to', 20)->nullable()->after('created_by');
            $table->unsignedBigInteger('training_program_id')->nullable()->after('assign_to');
            $table->unsignedBigInteger('training_batch_id')->nullable()->after('training_program_id');
            $table->index(['assign_to', 'training_program_id']);
            $table->index(['assign_to', 'training_batch_id']);
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropIndex(['assign_to', 'training_program_id']);
            $table->dropIndex(['assign_to', 'training_batch_id']);
            $table->dropColumn(['assign_to', 'training_program_id', 'training_batch_id']);
        });
    }
};
