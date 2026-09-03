<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_batch_trainers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_batch_id');
            $table->unsignedBigInteger('trainer_id');
            $table->string('role')->default('trainer'); // lead_trainer, co_trainer, guest_speaker
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_batch_trainers');
    }
};
