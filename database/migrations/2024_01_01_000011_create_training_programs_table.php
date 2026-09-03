<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title', 500);
            $table->text('description')->nullable();
            $table->string('category', 50)->default('optional');
            $table->string('type', 50)->default('course');
            $table->integer('duration_days')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->decimal('budget_allocated', 15, 2)->nullable();
            $table->text('objectives')->nullable();
            $table->text('target_audience')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('min_participants')->nullable();
            
            // Organization conducting the training
            $table->unsignedBigInteger('conducting_organization_id')->nullable();
            
            // Status workflow
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'ongoing', 'completed', 'cancelled', 'archived'])->default('draft');
            
            // Approval workflow
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_programs');
    }
};
