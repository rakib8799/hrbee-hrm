<?php

namespace App\Models\HR;

use App\Constants\Constants;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_attendance_requests";

    protected $fillable = [
        'user_id',
        'employee_id',
        'manager_id',
        'attendance_date',
        'check_in',
        'check_out',
        'work_from',
        'status',
        'approved_by',
        'declined_by',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
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
        
        if ($eventName === 'created') {
            return "Created new attendnce request of $this->attendance_date";
        } else if ($eventName === 'updated') {
            if ($this->status === Constants::APPROVED) {
                return "Approved attendance request of $this->attendance_date of $employeeName";
            } else {
                return "Declined attendance request of $this->attendance_date of $employeeName";
            }
        }

        // Default description if event is not handled
        return "Attendance Request {$eventName}.";
    }
}
