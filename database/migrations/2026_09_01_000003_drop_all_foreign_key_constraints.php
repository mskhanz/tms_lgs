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

        $database = DB::getDatabaseName();

        $foreignKeys = DB::select(
            'SELECT TABLE_NAME, CONSTRAINT_NAME
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_TYPE = ?
               AND TABLE_SCHEMA = ?
             ORDER BY TABLE_NAME, CONSTRAINT_NAME',
            ['FOREIGN KEY', $database]
        );

        foreach ($foreignKeys as $foreignKey) {
            DB::statement(sprintf(
                'ALTER TABLE `%s` DROP FOREIGN KEY `%s`',
                str_replace('`', '``', $foreignKey->TABLE_NAME),
                str_replace('`', '``', $foreignKey->CONSTRAINT_NAME)
            ));
        }
    }

    public function down(): void
    {
        // Foreign keys are intentionally not restored.
    }
};
