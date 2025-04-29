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
        Schema::create('hr_attendance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('employee_id')->constrained('hr_employees');
            $table->foreignId('manager_id')->nullable()->constrained('hr_employees');
            $table->date('attendance_date'); 
            $table->timestamp('check_in')->nullable(); // first check in time
            $table->timestamp('check_out')->nullable(); // last check out time
            $table->string('work_from'); // ['Work from home', 'Work from office', 'Work remotely']
            $table->string('status')->index(); // ['pending', 'approved', 'declined']->default('pending')
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('declined_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_attendance_requests');
    }
};
