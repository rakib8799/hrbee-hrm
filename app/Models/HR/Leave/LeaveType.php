<?php

namespace App\Models\HR\Leave;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_leave_types";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'color',
        'sequence',
        'leave_validation_type',
        'request_unit',
        'is_active',
        'is_editable',
        'is_deletable',
        'central_id'
    ];

    public function leaveAllocation()
    {
        return $this->hasMany(LeaveAllocation::class);
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
        if ($eventName === 'created') {
            return "Created new leave type - $this->name";
        } elseif ($eventName === 'updated') {
            if ($this->isDirty('is_active')) {
                return "Changed the status of leave type - $this->name";
            }
            return "Updated the leave type - $this->name";
        }

        // Default description if event is not handled
        return "Leave type {$eventName}.";
    }
}
