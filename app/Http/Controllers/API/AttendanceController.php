<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateAttendanceRequest;
use App\Services\HR\Employee\AttendanceLogService;

class AttendanceController extends Controller
{
    protected AttendanceLogService $attendanceLogService;

    public function __construct(AttendanceLogService $attendanceLogService)
    {
        $this->attendanceLogService = $attendanceLogService;
    }

    public function store(CreateAttendanceRequest $request, $staffId)
    {
        $tenantDomain = $request->header('X-Tenant-Domain');
        $validatedData = $request->validated();
        $attendance = $this->attendanceLogService->createAttendanceLog($validatedData);

        if($attendance) {
            $responseData = [
                'success' => true,
                'message' => 'New attendance created',
                'data' => [
                    'attendance' => $attendance
                ]
            ];
            $httpStatus = 201;
        } else {
            $responseData = [
                'success' => false,
                'message' => 'Staff not found',
                'data' => []
            ];
            $httpStatus = 404;
        }
        return response()->json($responseData, $httpStatus);
    }
}
