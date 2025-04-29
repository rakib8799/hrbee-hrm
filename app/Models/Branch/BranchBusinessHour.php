<?php

namespace App\Models\Branch;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchBusinessHour extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'day',
        'opening_time',
        'closing_time',
        'created_by',
        'updated_by'
    ];
}
