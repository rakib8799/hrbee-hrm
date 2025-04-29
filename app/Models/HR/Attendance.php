<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = "hr_attendances";

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in',
        'check_out',
        'worked_hours',
        'effective_hours',
        'work_from',
        'note'
    ];

    /**
     * Get the employee that owns the attendance
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
