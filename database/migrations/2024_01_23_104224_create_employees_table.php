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
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('country_id')->nullable()->constrained('countries');
            $table->date('dob');
            $table->string('gender')->nullable();   // [male,female,others]
            $table->string('marital_status')->nullable();   // [single,married,legal-cohabitant,widower,divorced]
            $table->string('social_security_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('address_line_one')->nullable();
            $table->string('address_line_two')->nullable();
            $table->string('floor')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->json('job_positions')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('hr_departments');
            $table->foreignId('branch_id')->nullable()->constrained('branches');
            $table->foreignId('manager_id')->nullable()->constrained('hr_employees');
            $table->foreignId('leave_manager_id')->nullable()->constrained('hr_employees');
            $table->string('staff_id')->unique();
            $table->float('salary')->nullable();
            $table->float('hourly_rate')->nullable();
            $table->integer('hour_limit_weekly')->nullable();
            $table->float('over_time_rate')->nullable();
            $table->integer('over_time_limit')->nullable();
            $table->date('joining_date')->nullable();
            $table->boolean('is_nfc_card_issued')->default(false);
            $table->boolean('is_geo_fencing_enabled')->default(false);
            $table->boolean('is_photo_taking_enabled')->default(false);
            $table->string('job_title')->nullable();
            $table->foreignId('departure_reason_id')->nullable()->constrained('hr_departure_reasons');
            $table->date('departure_date')->nullable();
            $table->text('departure_description')->nullable();
            $table->bigInteger('work_permit_number')->nullable();
            $table->date('work_permit_expiration_date')->nullable();
            $table->string('blood_group')->nullable();  // [A+,A-,B+,B-,AB+,AB-,O+,O-]
            $table->string('linkedin_profile')->nullable();
            $table->bigInteger('etin_number')->nullable();
            $table->string('passport_number')->nullable();
            $table->bigInteger('visa_number')->nullable();
            $table->date('visa_expire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_departed')->default(false);
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
        Schema::dropIfExists('hr_employees');
    }
};
