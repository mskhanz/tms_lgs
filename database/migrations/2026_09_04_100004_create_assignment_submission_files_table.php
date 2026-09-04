<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_submission_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('file_size')->default(0);
            $table->timestamps();

            $table->index('submission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_files');
    }
};
