<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\HR\Employee\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function getEmployeeDetails(Request $request, $staffId): JsonResponse
    {
        $employeeData = $this->employeeService->getEmployeeDetailsForAttendance($staffId);

        if($employeeData)
        {
            $responseData = [
                "success" => true,
                "message" => "Employee details",
                "data" => [
                    'employee' => $employeeData,
                ]
            ];
            $httpStatus = Response::HTTP_OK;
        } else {
            $responseData = [
                "success" => false,
                "message" => "Employee not found",
                "error_code" => Response::HTTP_NOT_FOUND,
                "data" => []
            ];
            $httpStatus = Response::HTTP_NOT_FOUND;
        }
        return response()->json($responseData, $httpStatus);
    }
}
