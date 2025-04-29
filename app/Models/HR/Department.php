<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Department extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_departments";

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
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
            return "Created new department - $this->name";
        } elseif ($eventName === 'updated') {
            if ($this->isDirty('is_active')) {
                return "Changed the status of the department - " . $this->name;
            }
            return "Updated the department - $this->name";
        } elseif ($eventName === 'deleted') {
            return "Deleted the department - $this->name";
        }

        // Default description if event is not handled
        return "Department {$eventName}.";
    }
}
