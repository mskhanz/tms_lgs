<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SchemaCache
{
    public static function hasTable(string $table): bool
    {
        return (bool) Cache::remember('schema.has_table.'.$table, now()->addHours(6), function () use ($table) {
            return Schema::hasTable($table);
        });
    }

    public static function hasColumn(string $table, string $column): bool
    {
        return (bool) Cache::remember('schema.has_column.'.$table.'.'.$column, now()->addHours(6), function () use ($table, $column) {
            return Schema::hasColumn($table, $column);
        });
    }
}
