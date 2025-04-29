<?php

namespace App\Models\Branch;

use App\Models\HR\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Branch extends Model
{
    use HasFactory, LogsActivity;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'manager_id',
        'branch_code',
        'phone_number',
        'address_line_one',
        'address_line_two',
        'floor',
        'city',
        'state',
        'zip_code',
        'geo_location',
        'day_off',
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
            return "Created new branch - $this->name";
        } elseif ($eventName === 'updated') {
            if ($this->isDirty('is_active')) {
                return "Changed the status of branch - ". $this->name;
            }
            return "Updated the branch - $this->name";
        } elseif ($eventName === 'deleted') {
            return "Deleted the branch - $this->name";
        }

        // Default description if event is not handled
        return "Branch {$eventName}.";
    }
}
