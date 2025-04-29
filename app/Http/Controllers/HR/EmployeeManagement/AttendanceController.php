<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Models\HR\Employee;
use App\Services\HR\DepartureReasonService;
use App\Services\HR\Employee\AttendanceService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;

class AttendanceController extends Controller implements HasMiddleware
{
    protected AttendanceService $attendanceService;
    protected DepartureReasonService $departureReasonService;

    public function __construct(AttendanceService $attendanceService, DepartureReasonService $departureReasonService)
    {
        $this->attendanceService = $attendanceService;
        $this->departureReasonService = $departureReasonService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-employee-attendance', only: ['index']),
            new middleware('employee.departed', only: ['index'])
        ];
    }

    public function index(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('attendance', $employee);
        $employeeAttendances = $this->attendanceService->getEmployeeAttendances($employee);
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $responseData = [
            'employee' => $employee,
            'employeeAttendances' => $employeeAttendances,
            'departureReasons' => $departureReasons,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendance'),
        ];
        return Inertia::render('Attendance/Index', $responseData);
    }
}
