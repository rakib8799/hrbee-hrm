<?php

namespace App\Services\HR\Employee;

use App\Constants\Constants;
use App\Models\HR\Employee;
use App\Models\HR\Attendance;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use Carbon\Carbon;

class AttendanceService extends BaseModelService
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function model(): string
    {
        return Attendance::class;
    }

    public function getEmployeeAttendances(Employee $employee)
    { 
        $employeeAttendances = $this->model()::where('employee_id', $employee->id)->orderBy('attendance_date', 'desc')->get();

        return collect($employeeAttendances)->map(function ($employeeAttendance) {
            $employeeAttendance->work_location_text = HelperService::getWorkLocation($employeeAttendance->work_from);
    
            return $employeeAttendance;
        });
    }

    public function getEmployeeAttendancesForMonth(Employee $employee, $year, $month)
    {
        $attendances = $this->model()::where('employee_id', $employee->id)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get();

        $formattedAttendanceData = [];

        foreach ($attendances as $attendance) {
            $date = Carbon::parse($attendance->attendance_date);
            $day = $date->format('d'); 
            $formattedAttendanceData[$day] = 1;
        }

        $responseData = [
            'attendanceName' => Constants::LABEL_ATTENDANCES,
            'formattedAttendanceData' => $formattedAttendanceData,
            'attendanceColor' => Constants::COLOR_RED
        ];

        return $responseData;
    }

    public function getEmployeeTodayAttendance()
    {
        $today = Carbon::now()->format('Y-m-d');
        $employeeTodayAttendance = $this->model()::whereDate('attendance_date', $today)->get();

        $employees = $this->employeeService->getActiveEmployees();
        $formattedAttendanceData = $employees->pluck('id')
            ->mapWithKeys(function ($employeeId) use ($employeeTodayAttendance) {
                $attendance = $employeeTodayAttendance->firstWhere('employee_id', $employeeId);
                $status = $attendance && $attendance->check_in ? Constants::PRESENT : Constants::ABSENT;
                $checkInTime = $attendance && $attendance->check_in ? $attendance->check_in : null;
                
                return [
                    $employeeId => [
                        'status' => $status,
                        'checkInTime' => $checkInTime
                    ]
                ];
            }
        );

        $responseData = [
            'employees' => $employees,
            'attendances' => [
                'attendanceDate' => $today,
                'formattedAttendanceData' => $formattedAttendanceData, 
                'attendanceColor' => [
                    'present' => Constants::COLOR_GREEN,
                    'absent' => Constants::COLOR_RED
                ]
            ]
        ];

        return $responseData;
    }
}

