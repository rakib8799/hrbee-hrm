<?php

namespace App\Models\HR\Leave;

use App\Models\HR\Employee;
use App\Constants\Constants;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_leaves";

    protected $fillable = [
        'name',
        'employee_id',
        'leave_type_id',
        'request_hour_from',
        'request_hour_to',
        'request_date_from_period',
        'request_date_from',
        'request_date_to',
        'first_approver_id',
        'second_approver_id',
        'user_id',
        'manager_id',
        'department_id',
        'status',
        'duration_display',
        'holiday_type',
        'note',
        'is_active',
        'request_unit_half',
        'request_unit_hours',
        'date_from',
        'date_to',
        'number_of_days',
        'number_of_hours',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
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
        $leaveTypeName = $this->leaveType->name;
        $employeeName = $employee->getFullName();

        if ($eventName === 'created') {
            if ($this->status === Constants::APPROVED) {
                return "Created and auto approved new leave request - $this->name of ". $leaveTypeName;
            } else {
                return "Created new leave request - $this->name of ". $leaveTypeName;
            }
        } elseif ($eventName === 'updated') {
            if ($this->status === Constants::DECLINED) {
                return "Leave request of $leaveTypeName of $employeeName declined";
            } else if ($this->status === Constants::APPROVED_FIRST) {
                return "Leave request of $leaveTypeName of $employeeName approved first";
            } else {
                return "Leave request of $leaveTypeName of $employeeName approved";
            }
        }

        // Default description if event is not handled
        return "Leave request {$eventName}.";
    }
}
