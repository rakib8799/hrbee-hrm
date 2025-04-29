<?php

namespace App\Http\Controllers\HR\Employee;

use App\Http\Controllers\Controller;
use App\Services\HR\Employee\AttendanceService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;

class EmployeeAttendanceController extends Controller implements HasMiddleware
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-employee-attendance', only: ['index'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('employeeAttendances');
        $employee = auth()->user()->employee;
        $employeeAttendances = $this->attendanceService->getEmployeeAttendances($employee);
        $responseData = [
            'employeeAttendances' => $employeeAttendances,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendance'),
        ];
        return Inertia::render('EmployeeAttendance/Attendance', $responseData);
    }
}
