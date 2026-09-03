<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('cnic', 15)->unique();
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('qualifications')->nullable();
            $table->text('expertise')->nullable();
            $table->text('experience')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('organization')->nullable();
            $table->string('designation')->nullable();
            $table->string('profile_picture')->nullable();
            $table->text('cv_file')->nullable();
            
            // Empanelment
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->date('empanelment_date')->nullable();
            $table->date('empanelment_expiry')->nullable();
            
            // Approval
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
