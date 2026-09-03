<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_nominations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainee_id');
            $table->unsignedBigInteger('training_batch_id');
            $table->unsignedBigInteger('nominated_by'); // Department head/admin
            $table->unsignedBigInteger('organization_id');
            $table->text('nomination_reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'withdrawn'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_nominations');
    }
};
