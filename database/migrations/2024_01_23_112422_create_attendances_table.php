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

        Schema::create('hr_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('hr_employees');
            $table->date('attendance_date')->index(); // date of attendance
            $table->timestamp('check_in'); // first check in time
            $table->timestamp('check_out')->nullable(); // last check out time
            $table->string('worked_hours')->nullable();
            $table->string('effective_hours')->nullable();
            $table->string('work_from')->nullable(); // ['Work from home', 'Work from office', 'Work remotely']
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_attendances');
    }
};
