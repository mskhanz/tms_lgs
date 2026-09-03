<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('ministry', 'department', 'attached_department', 'institute', 'tma') NOT NULL");
        }

        Schema::table('organizations', function (Blueprint $table) {
            if (! Schema::hasColumn('organizations', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('parent_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'district_id')) {
                $table->dropColumn('district_id');
            }
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE organizations MODIFY COLUMN type ENUM('ministry', 'department', 'attached_department', 'institute') NOT NULL");
        }
    }
};
