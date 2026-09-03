<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE training_programs MODIFY COLUMN category VARCHAR(50) NOT NULL DEFAULT 'optional'");
        DB::statement("ALTER TABLE training_programs MODIFY COLUMN type VARCHAR(50) NOT NULL DEFAULT 'course'");
        DB::statement('ALTER TABLE training_programs MODIFY COLUMN title VARCHAR(500) NOT NULL');
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE training_programs MODIFY COLUMN category ENUM('mandatory','refresher','optional') NOT NULL DEFAULT 'optional'");
        DB::statement("ALTER TABLE training_programs MODIFY COLUMN type ENUM('course','training','workshop','seminar') NOT NULL DEFAULT 'course'");
        DB::statement('ALTER TABLE training_programs MODIFY COLUMN title VARCHAR(255) NOT NULL');
    }
};
