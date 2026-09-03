<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->unsignedBigInteger('enrollment_id');
            $table->unsignedBigInteger('trainee_id');
            $table->unsignedBigInteger('training_batch_id');
            $table->string('certificate_file')->nullable();
            $table->string('qr_code')->nullable(); // For verification
            $table->date('issue_date');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('issued_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['pending', 'issued', 'revoked'])->default('issued');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
