<?php

namespace App\Models\HR;

use Carbon\Carbon;
use App\Constants\Constants;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory, LogsActivity;

    use SoftDeletes;

    protected $table = "hr_attendance_logs";

    protected $fillable = [
        'employee_id',
        'punch_time',
        'punch_type',
        'note',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the employee that owns the attendance
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

     /**
     * Add Activity to activityLogs table
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    /**
     * Changed the activity description of events
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        $employee = $this->employee;
        $employeeName = $employee->getFullName();
        $punchDate = Carbon::parse($this->punch_time)->toDateString();

        if ($eventName === 'created') {
            if ($this->punch_type === Constants::IN) {
                return "Created new attendance log - Check In of $punchDate of $employeeName";
            } else if ($this->punch_type === Constants::OUT) {
                return "Created new attendance log - Check Out of $punchDate of $employeeName";
            } else {
                return "Created new attendance log - Break of $punchDate of $employeeName";
            }
        }

        // Default description if event is not handled
        return "Attendance {$eventName}.";
    }
}
