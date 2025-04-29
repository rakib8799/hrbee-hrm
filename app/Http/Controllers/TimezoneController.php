<?php

namespace App\Http\Controllers;

use App\Services\ConfigurationService;

class TimezoneController extends Controller
{
    protected ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    public function getTimezoneAndDateFormat()
    {
        $timezone = $this->configurationService->getTimezone();
        $dateFormat = $this->configurationService->getDateFormat();
        
        $statusCode = 200;
        $responseData = [
            'timezone' => $timezone,
            'date_format' => $dateFormat
        ];
        
        return response()->json($responseData, $statusCode);
    }
}
