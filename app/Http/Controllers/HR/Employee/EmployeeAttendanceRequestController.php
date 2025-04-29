<?php

namespace App\Http\Controllers\HR\Employee;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Employee\CreateAttendanceRequest;
use App\Services\Core\HelperService;
use App\Services\HR\Employee\EmployeeService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Services\HR\Employee\AttendanceRequestService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class EmployeeAttendanceRequestController extends Controller implements HasMiddleware
{
    protected AttendanceRequestService $attendanceRequestService;
    protected EmployeeService $employeeService;

    public function __construct(AttendanceRequestService $attendanceRequestService, EmployeeService $employeeService)
    {
        $this->attendanceRequestService = $attendanceRequestService;
        $this->employeeService = $employeeService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-employee-attendance-request', only: ['store']),
            new Middleware('permission:can-view-employee-attendance-request', only: ['index'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('employeeAttendanceRequests');
        $employee = auth()->user()->employee;
        $employeeAttendanceRequests = $this->attendanceRequestService->getEmployeeAttendanceRequests($employee);
        $employeeJoiningDate = $employee->joining_date;
        $requestStatuses = HelperService::getRequestStatuses();
        $workLocations = HelperService::getWorkLocations();
        $responseData = [
            'employeeAttendanceRequests' => $employeeAttendanceRequests,
            'employeeJoiningDate' => $employeeJoiningDate,
            'requestStatuses' => $requestStatuses,
            'workLocations' => $workLocations,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendanceRequest'),
        ];
        return Inertia::render('EmployeeAttendance/AttendanceRequest', $responseData);
    }

    public function store(CreateAttendanceRequest $request)
    {
        $validatedData = $request->validated();
        $user = auth()->user();
        $employee = $user->employee;
        $missingAttendance = $this->attendanceRequestService->createAttendanceRequest($user, $employee, $validatedData);
        $status = $missingAttendance ? Constants::SUCCESS : Constants::ERROR;
        $message = $missingAttendance ? __('message.custom.employee.attendanceRequest.store.success') : __('message.custom.employee.attendanceRequest.store.error');
        return Redirect::route('employee-attendance-requests.index')->with($status, $message);
    }
}
