<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Configuration extends Model
{
    use HasFactory, LogsActivity;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'option_name',
        'option_value'
    ];

    // Cast 'weekends' attribute to array
    protected $casts = [
        'weekends' => 'array'
    ];

    /**
     * Get corresponding country of an employee
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

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
        if ($eventName === 'updated') {
            return "Updated the configuration";
        }

        // Default description if event is not handled
        return "Configuration {$eventName}.";
    }
}
