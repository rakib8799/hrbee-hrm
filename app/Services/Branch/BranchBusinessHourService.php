<?php

namespace App\Services\Branch;

use App\Services\Core\BaseModelService;

class BranchBusinessHourService extends BaseModelService
{
    public function model(): string
    {
        return BranchBusinessHourService::class;
    }
}
