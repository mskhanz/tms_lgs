<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            
            // Personal Information
            $table->string('cnic_no', 15)->unique();
            $table->string('emp_name');
            $table->string('father_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('personal_no')->nullable();
            $table->enum('trainee_type', ['regular', 'contractual', 'honorary', 'consultant'])->default('regular');
            $table->date('dob')->nullable();
            $table->string('domicile')->nullable();
            $table->string('cadre')->nullable();
            $table->unsignedBigInteger('service_status_id')->nullable();
            $table->string('emp_email')->nullable();
            $table->string('emp_whatsapp_no')->nullable();
            $table->string('contact_no');
            $table->date('date_of_initial_appointment')->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('current_address')->nullable();
            $table->text('remarks')->nullable();
            $table->string('file_picture')->nullable();
            
            // Current Posting Details
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('tehsil_id')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('designation')->nullable();
            $table->integer('bps')->nullable();
            $table->string('status')->nullable();
            $table->date('from_date')->nullable();
            
            // Audit fields
            $table->unsignedBigInteger('completed_by')->nullable(); // Trainer who completed profile
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_profiles');
    }
};
