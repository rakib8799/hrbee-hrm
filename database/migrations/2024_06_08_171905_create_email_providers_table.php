<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('email_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('api_key')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('base_url')->nullable();
            $table->string('from_email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('central_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_providers');
    }
};
