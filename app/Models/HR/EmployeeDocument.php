<?php

namespace App\Models\HR;

use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeDocument extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_employee_documents";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
     protected $fillable = [
        'name',
        'file_path',
        'employee_id',
        'source',
        'document_type_id',
        'created_by',
        'updated_by'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }
}
