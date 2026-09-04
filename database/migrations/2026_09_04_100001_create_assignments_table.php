<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->enum('assign_to', ['program', 'batch'])->nullable();
            $table->unsignedBigInteger('training_program_id')->nullable();
            $table->unsignedBigInteger('training_batch_id')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['assign_to', 'training_program_id']);
            $table->index(['assign_to', 'training_batch_id']);
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
