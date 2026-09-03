<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_program_id');
            $table->string('batch_code', 50)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('venue')->nullable();
            $table->text('venue_address')->nullable();
            $table->integer('total_seats')->default(30);
            $table->integer('seats_filled')->default(0);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->text('remarks')->nullable();
            
            // Coordinator/Officer managing this batch
            $table->unsignedBigInteger('coordinator_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_batches');
    }
};
