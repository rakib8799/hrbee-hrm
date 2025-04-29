<?php

use App\Constants\Constants;
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
        Schema::create('hr_leaves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('employee_id')->constrained('hr_employees');
            $table->foreignId('leave_type_id')->constrained('hr_leave_types');
            $table->timestamp('request_hour_from')->nullable();
            $table->timestamp('request_hour_to')->nullable();
            $table->string('request_date_from_period')->default('am');
            $table->date('request_date_from');
            $table->date('request_date_to');
            $table->foreignId('first_approver_id')->nullable()->constrained('hr_employees');
            $table->foreignId('second_approver_id')->nullable()->constrained('hr_employees');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('manager_id')->nullable()->constrained('hr_employees');
            $table->foreignId('department_id')->nullable()->constrained('hr_departments');
            $table->string('status')->default(Constants::PENDING);    // pending, approved, declined
            $table->string('duration_display')->nullable();
            $table->string('holiday_type');     // company, employee, department
            $table->string('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('request_unit_half')->nullable();
            $table->string('request_unit_hours')->nullable();
            $table->date('date_from');
            $table->date('date_to');
            $table->double('number_of_days');
            $table->integer('number_of_hours');
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
        Schema::dropIfExists('hr_leaves');
    }
};
