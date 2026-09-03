<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainee_id');
            $table->unsignedBigInteger('training_batch_id');
            $table->unsignedBigInteger('nomination_id')->nullable();
            $table->unsignedBigInteger('enrolled_by'); // Admin/Training Officer
            $table->date('enrollment_date');
            $table->enum('status', ['enrolled', 'in_progress', 'completed', 'dropped', 'failed'])->default('enrolled');
            $table->date('completion_date')->nullable();
            $table->decimal('attendance_percentage', 5, 2)->default(0);
            $table->decimal('assessment_score', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_enrollments');
    }
};
