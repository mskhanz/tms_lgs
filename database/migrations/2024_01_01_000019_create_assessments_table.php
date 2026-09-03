<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_program_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['pre_test', 'post_test', 'quiz', 'assignment', 'practical', 'final_exam'])->default('final_exam');
            $table->decimal('total_marks', 5, 2)->default(100);
            $table->decimal('passing_marks', 5, 2)->default(50);
            $table->integer('duration_minutes')->nullable();
            $table->date('assessment_date')->nullable();
            $table->enum('status', ['draft', 'active', 'completed', 'archived'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
