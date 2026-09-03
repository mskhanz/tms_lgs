<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->enum('type', ['ministry', 'department', 'attached_department', 'institute']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
