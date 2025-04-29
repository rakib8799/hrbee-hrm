<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Branch\BranchService;

class BranchController extends Controller
{
    protected BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }
}
