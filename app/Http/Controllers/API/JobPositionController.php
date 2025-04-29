<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HR\JobPositionService;

class JobPositionController extends Controller
{
    protected JobPositionService $jobPositionService;

    public function __construct(JobPositionService $jobPositionService)
    {
        $this->jobPositionService = $jobPositionService;
    }
}
