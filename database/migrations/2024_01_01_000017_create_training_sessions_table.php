<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_batch_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue')->nullable();
            $table->unsignedBigInteger('trainer_id')->nullable();
            $table->text('topics_covered')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
