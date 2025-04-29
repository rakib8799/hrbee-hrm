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
        Schema::create('hr_calendar_leaves', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->foreignId('employee_id')->nullable()->constrained('hr_employees');
            $table->string('name')->nullable();
            $table->string('time_type')->default('leave'); // leave, other
            $table->date('date_from');
            $table->date('date_to');
            $table->foreignId('leave_id')->nullable()->constrained('hr_leaves');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_calendar_leaves');
    }
};
