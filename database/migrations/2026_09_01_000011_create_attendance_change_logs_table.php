<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_change_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_record_id')->nullable();
            $table->unsignedBigInteger('training_session_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('trainee_id');
            $table->unsignedBigInteger('changed_by');
            $table->date('session_date');
            $table->string('action', 20);
            $table->string('old_status')->nullable();
            $table->string('new_status')->nullable();
            $table->timestamp('old_check_in_time')->nullable();
            $table->timestamp('new_check_in_time')->nullable();
            $table->string('old_remarks')->nullable();
            $table->string('new_remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_change_logs');
    }
};
