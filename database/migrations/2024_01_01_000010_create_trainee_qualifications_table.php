<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_qualifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trainee_profile_id');
            $table->unsignedBigInteger('degree_id');
            $table->string('institute');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->year('passing_year');
            $table->string('percentage_marks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_qualifications');
    }
};
