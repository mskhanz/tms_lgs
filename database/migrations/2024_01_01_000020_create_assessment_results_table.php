<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('trainee_id');
            $table->decimal('obtained_marks', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->enum('grade', ['A+', 'A', 'B', 'C', 'D', 'F'])->nullable();
            $table->enum('result', ['pass', 'fail'])->default('fail');
            $table->text('feedback')->nullable();
            $table->unsignedBigInteger('evaluated_by')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->enum('status', ['pending', 'evaluated', 'approved'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_results');
    }
};
