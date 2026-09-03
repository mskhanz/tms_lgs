<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('logged_in_at');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();
            $table->string('logout_reason', 30)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'logged_out_at']);
            $table->index(['session_id', 'logged_out_at']);
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_sessions');
    }
};
