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
        Schema::create('hr_leave_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('number_of_days');
            $table->foreignId('leave_type_id')->constrained('hr_leave_types');
            $table->foreignId('employee_id')->nullable()->constrained('hr_employees');
            $table->foreignId('manager_id')->nullable()->constrained('hr_employees');
            $table->foreignId('department_id')->nullable()->constrained('hr_departments');
            $table->foreignId('parent_id')->nullable()->constrained('hr_leave_allocations');
            $table->foreignId('approver_id')->nullable()->constrained('users');
            $table->string('holiday_type');  //company, employee, department
            $table->string('allocation_type');  // regular
            $table->date('date_from');
            $table->date('date_to');
            $table->string('notes')->nullable();
            $table->string('status');   // pending, approved, declined
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('hr_leave_allocations');
    }
};
