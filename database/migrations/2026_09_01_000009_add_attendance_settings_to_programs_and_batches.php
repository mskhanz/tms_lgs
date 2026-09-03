<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->boolean('attendance_enabled')->default(false)->after('status');
            $table->unsignedTinyInteger('min_attendance_percentage')->nullable()->after('attendance_enabled');
        });

        Schema::table('training_batches', function (Blueprint $table) {
            $table->boolean('attendance_enabled')->default(false)->after('status');
            $table->unsignedTinyInteger('min_attendance_percentage')->nullable()->after('attendance_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('training_batches', function (Blueprint $table) {
            $table->dropColumn(['attendance_enabled', 'min_attendance_percentage']);
        });

        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropColumn(['attendance_enabled', 'min_attendance_percentage']);
        });
    }
};
