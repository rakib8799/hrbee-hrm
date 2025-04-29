<?php

namespace App\Services;

use App\Models\Timezone;
use App\Services\Core\BaseModelService;

class TimezoneService extends BaseModelService
{
    public function model(): string
    {
        return Timezone::class;
    }

    public function getActiveTimezones()
    {
        return $this->model()::where('is_active', true)->get();
    }
}
