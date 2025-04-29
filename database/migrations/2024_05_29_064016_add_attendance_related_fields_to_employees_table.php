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
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->foreignId('last_attendance_id')->nullable()->constrained('hr_attendances');
            $table->timestamp('last_check_in')->nullable();
            $table->timestamp('last_check_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->dropForeign(['last_attendance_id']);
            $table->dropColumn('last_attendance_id');
            $table->dropColumn('last_check_in');
            $table->dropColumn('last_check_out');
        });
    }
};
