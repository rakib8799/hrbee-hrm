<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Branch\BranchBusinessHour;

class BranchBusinessHourController extends Controller
{
    protected BranchBusinessHour $branchBusinessHour;

    public function __construct(BranchBusinessHour $branchBusinessHour)
    {
        $this->branchBusinessHour = $branchBusinessHour;
    }
}
