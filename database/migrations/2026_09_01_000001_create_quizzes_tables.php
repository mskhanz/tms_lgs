<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedTinyInteger('passing_percentage')->default(50);
            $table->unsignedTinyInteger('max_attempts')->default(1);
            $table->boolean('shuffle_questions')->default(true);
            $table->boolean('shuffle_options')->default(true);
            $table->boolean('is_active')->default(false);
            $table->boolean('show_results')->default(true);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->string('part')->nullable();
            $table->text('question_text');
            $table->unsignedTinyInteger('marks')->default(1);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->unsignedSmallInteger('correct_answers')->default(0);
            $table->decimal('score', 8, 2)->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->enum('status', ['in_progress', 'completed', 'expired'])->default('in_progress');
            $table->json('question_order');
            $table->json('option_orders');
            $table->timestamps();

            $table->index(['quiz_id', 'user_id']);
        });

        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('selected_option_id')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
