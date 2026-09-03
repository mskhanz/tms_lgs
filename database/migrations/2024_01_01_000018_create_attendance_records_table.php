<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('training_session_id');
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('trainee_id');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->enum('marking_method', ['manual', 'qr_code', 'biometric'])->default('manual');
            $table->unsignedBigInteger('marked_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
