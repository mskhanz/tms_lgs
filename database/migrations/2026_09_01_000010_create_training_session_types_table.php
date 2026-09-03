<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_session_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('training_session_types')->insert([
            ['name' => '1st Session', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '2nd Session', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('training_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('training_session_type_id')->nullable()->after('training_batch_id');
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn('training_session_type_id');
        });

        Schema::dropIfExists('training_session_types');
    }
};
