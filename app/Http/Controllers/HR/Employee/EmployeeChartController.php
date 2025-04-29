<?php

namespace App\Http\Controllers\HR\Employee;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Services\HR\Employee\AttendanceService;
use App\Services\HR\Leave\CalendarLeaveService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class EmployeeChartController extends Controller implements HasMiddleware
{
    protected CalendarLeaveService $calendarLeaveService;
    protected AttendanceService $attendanceService;

    public function __construct(CalendarLeaveService $calendarLeaveService, AttendanceService $attendanceService)
    {
        $this->calendarLeaveService = $calendarLeaveService;
        $this->attendanceService = $attendanceService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('role.isEmployee', only: ['getChartData']),
            new Middleware('permission:can-view-employee-chart', only: ['getChartData'])
        ];
    }

    public function getChartData(Request $request)
    {
        $employee = auth()->user()->employee;
        $year = $request->input('year');
        $month = $request->input('month');

        $days = $this->calendarLeaveService->getDaysForMonth($year, $month);
        $weekends = $this->calendarLeaveService->getChartWeekends($year, $month);
        $calendarLeaves = $this->calendarLeaveService->getEmployeeChartCalendarLeaves($employee, $year, $month);
        $attendances = $this->attendanceService->getEmployeeAttendancesForMonth($employee, $year, $month);

        $responseData = [
            'days' => $days,
            'weekends' => $weekends,
            'calendarLeaves' => $calendarLeaves,
            'attendances' => $attendances
        ];

        $statusCode = 200;
        return response()->json($responseData, $statusCode);
    }
}
