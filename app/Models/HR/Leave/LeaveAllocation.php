<?php

namespace App\Models\HR\Leave;

use App\Models\HR\Employee;
use App\Constants\Constants;
use App\Models\HR\Department;
use App\Models\HR\Leave\LeaveType;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAllocation extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_leave_allocations";

    protected $fillable = [
        'name',
        'number_of_days',
        'leave_type_id',
        'employee_id',
        'manager_id',
        'department_id',
        'parent_id',
        'approver_id',
        'holiday_type',
        'allocation_type',
        'date_from',
        'date_to',
        'notes',
        'status',
        'is_active',
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

    public function department()
    {
        return $this->belongsTo(Department::class);
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
        
        if ($eventName === 'created') {
            return "Created new leave allocation - $this->name". ($employee ? ' for '. $employee->getFullName() : '');
        } elseif ($eventName === 'updated') {
            if($this->isDirty('status') && $this->status === Constants::APPROVED) {
                return "Approved leave allocation - $this->name". ($employee ? ' of '. $employee->getFullName() : '');
            } else if($this->isDirty('status') && $this->status === Constants::DECLINED) {
                return "Declined leave allocation - $this->name". ($employee ? ' for '. $employee->getFullName() : '');
            } else if ($this->isDirty('is_active')) {
                return "Changed the status of leave allocation - $this->name";
            }
            return "Updated the leave allocation - $this->name". ($employee ? ' for '. $employee->getFullName() : '');
        }

        // Default description if event is not handled
        return "Leave allocation {$eventName}.";
    }
}
