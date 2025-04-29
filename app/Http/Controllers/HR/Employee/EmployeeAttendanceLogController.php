<?php

namespace App\Http\Controllers\HR\Employee;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Employee\CreateEmployeeAttendanceLogRequest;
use App\Http\Requests\HR\Employee\CreateEmployeeCheckoutRequest;
use App\Services\Core\HelperService;
use App\Services\HR\Employee\AttendanceLogService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class EmployeeAttendanceLogController extends Controller implements HasMiddleware
{
    protected AttendanceLogService $attendanceLogService;

    public function __construct(AttendanceLogService $attendanceLogService)
    {
        $this->attendanceLogService = $attendanceLogService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-employee-attendance-log', only: ['store']),
            new Middleware('permission:can-view-employee-attendance-log', only: ['index'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('employeeAttendanceLogs');
        $employee = auth()->user()->employee;
        $employeeLastAttendance = $employee->lastAttendance->attendance_date ?? null;
        $employeeAttendanceLogs = $this->attendanceLogService->getEmployeeAttendanceLogs($employee);
        $hasPendingCheckout = $this->attendanceLogService->hasPendingCheckout($employee);
        $isFirstPunchOfDay = $this->attendanceLogService->isFirstPunchOfDay($employee);
        $punchTypes = HelperService::getPunchTypes();
        $workLocations = HelperService::getWorkLocations();
        $canPunch = $this->attendanceLogService->canPunch($employee);
        $responseData = [
            'employeeLastAttendance' => $employeeLastAttendance,
            'employeeAttendanceLogs' => $employeeAttendanceLogs,
            'punchTypes' => $punchTypes,
            'canPunch' => $canPunch,
            'hasPendingCheckout' => $hasPendingCheckout,
            'isFirstPunchOfDay' => $isFirstPunchOfDay,
            'workLocations' => $workLocations,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendanceLog'),
        ];
        return Inertia::render('EmployeeAttendance/AttendanceLog', $responseData);
    }

    public function store(CreateEmployeeAttendanceLogRequest $request)
    {
        $validatedData = $request->validated();
        $employee = auth()->user()->employee;
        $employeeAttendanceLog = $this->attendanceLogService->createEmployeeAttendanceLog($employee, $validatedData);
        $status = $employeeAttendanceLog ? Constants::SUCCESS : Constants::ERROR;
        $message = $employeeAttendanceLog ? __('message.custom.employee.attendanceLog.store.success') : __('message.custom.employee.attendanceLog.store.error');
        return Redirect::route('employee-attendance-logs.index')->with($status, $message);
    }

    public function checkout(CreateEmployeeCheckoutRequest $request)
    {
        $validatedData = $request->validated();
        $employee = auth()->user()->employee;
        $employeeAttendanceLog = $this->attendanceLogService->createEmployeeCheckout($employee, $validatedData);
        $status = $employeeAttendanceLog ? Constants::SUCCESS : Constants::ERROR;
        $message = $employeeAttendanceLog ? __('message.custom.employee.attendanceLog.checkout.success') : __('message.custom.employee.attendanceLog.checkout.error');
        return Redirect::route('employee-attendance-logs.index')->with($status, $message);
    }
}

