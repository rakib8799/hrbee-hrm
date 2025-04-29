<?php

namespace App\Models\HR\Leave;

use App\Models\HR\Employee;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalendarLeave extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_calendar_leaves";

    protected $fillable = [
        'year',
        'employee_id',
        'name',
        'time_type',
        'date_from',
        'date_to',
        'leave_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class);
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
            if (!$employee) {
                return "Created new public holiday - $this->name";
            } else {
                return "";
            }
        } elseif ($eventName === 'updated') {
            return "Updated the public holiday - $this->name";
        }

        // Default description if event is not handled
        return "Public holiday {$eventName}.";
    }
}
