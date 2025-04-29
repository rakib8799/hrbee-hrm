<?php

use App\Constants\Constants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('color');
            $table->integer('sequence')->unique();
            $table->string('leave_validation_type')->default(Constants::HR); // hr, manager, both, no validations
            $table->string('request_unit'); // day, hour
            $table->boolean('is_active')->default(true);
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_deletable')->default(true);
            $table->integer('central_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_leave_types');
    }
};
