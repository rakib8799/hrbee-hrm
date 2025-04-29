<?php

namespace App\Services\HR\Leave;

use App\Constants\Constants;
use App\Models\Configuration;
use App\Models\HR\Employee;
use App\Models\HR\Leave\CalendarLeave;
use App\Models\HR\Leave\Leave;
use App\Services\Core\BaseModelService;
use App\Services\HR\Leave\LeaveTypeService;
use Carbon\Carbon;

class CalendarLeaveService extends BaseModelService
{
    protected LeaveTypeService $leaveTypeService;
    public function __construct(LeaveTypeService $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    public function model(): string
    {
        return CalendarLeave::class;
    }

    public function getCalendarLeaves()
    {
        return $this->model()::all();
    }

    public function getEmployeeCalendarLeaves(Employee $employee)
    {
        return $this->model()::where('employee_id', $employee->id)->orWhere('employee_id', NULL)->with(['employee', 'leave.leaveType'])->get();
    }

    public function getDaysForMonth($year, $month)
    {
        $days = [];
        $date = Carbon::create($year, $month, 1);

        $lastDay = $date->daysInMonth;

        for ($day = 1; $day <= $lastDay; $day++) {
            $days[] = str_pad($day, 2, '0', STR_PAD_LEFT);
        }

        return $days;
    }

    public function getChartWeekends($year, $month)
    {
        $weekendsConfig = Configuration::where('option_name', 'weekends')->first();
        $weekends = json_decode($weekendsConfig->option_value, true);

        $formattedWeekendData = [];

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        while ($startDate <= $endDate) {
            $day = $startDate->format('d');

            if (in_array(strtolower($startDate->format('l')), $weekends)) {
                $formattedWeekendData[$day] = 1;
            }
            $startDate->addDay();
        }

        $responseData = [
            'weekendName' => Constants::LABEL_WEEKENDS,
            'formattedWeekendData' => $formattedWeekendData,
            'weekendColor' => Constants::COLOR_GRAY
        ];

        return $responseData;
    }

    public function getEmployeeChartCalendarLeaves(Employee $employee, $year, $month)
    {
        $calendarLeaves = $this->model()::where('employee_id', $employee->id)->orWhere('employee_id', NULL)
            ->whereYear('date_from', $year)
            ->whereMonth('date_from', $month)
            ->whereYear('date_to', $year)
            ->whereMonth('date_to', $month)
            ->with(['employee', 'leave.leaveType'])
            ->get();

        $formattedLeaveData = [];
        $leaveColors = [];
    
        foreach ($calendarLeaves as $calendarLeave) {
            $startDate = Carbon::parse($calendarLeave->date_from);
            $endDate = Carbon::parse($calendarLeave->date_to);
            $calendarLeaveName = $calendarLeave->name;
    
            while ($startDate->lte($endDate)) {
                if ($startDate->year == $year && $startDate->month == $month) {
                    $day = $startDate->format('d');
    
                    $formattedLeaveData[$calendarLeaveName][$day] = 1;
                    
                    $leaveColors[$calendarLeaveName] = $calendarLeave->leave->leaveType->color ?? Constants::COLOR_BLACK;
                }
    
                $startDate->addDay();
            }
    
        }
        return [
            'formattedLeaveData' => $formattedLeaveData,
            'leaveColors' => $leaveColors
        ];
    }

    public function getPublicHolidays()
    {
        return $this->model()::where('time_type', Constants::PUBLIC_HOLIDAY)->get();
    }

    public function createCalendarLeave(Leave $leave, $validatedData)
    {
        $leaveType = $this->leaveTypeService->find($leave->leave_type_id);
        $validatedData['year'] = Carbon::now()->year;
        $validatedData['employee_id'] = $leave->employee->id;
        $validatedData['name'] = $leaveType->name;
        $validatedData['date_from'] = $leave->date_from;
        $validatedData['date_to'] = $leave->date_to;
        $validatedData['leave_id'] = $leave->id;

        return $this->create($validatedData);
    }

    public function createPublicHoliday($validatedData)
    {
        $validatedData['year'] = now()->year;
        $validatedData['time_type'] = Constants::PUBLIC_HOLIDAY;
        $calendarLeave = $this->create($validatedData);
        return $calendarLeave;
    }

    public function updatePublicHoliday(CalendarLeave $calendarLeave, $validatedData)
    {
        $calendarLeave = $this->update($calendarLeave, $validatedData);
        return $calendarLeave;
    }

    public function deletePublicHoliday(CalendarLeave $calendarLeave)
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $parseDateFrom = Carbon::parse($calendarLeave->date_from);

        if (
            $calendarLeave->time_type === Constants::PUBLIC_HOLIDAY && 
            $parseDateFrom->year === $currentYear && $parseDateFrom->gt($currentDate)
        ) {
            $isDeleted = $this->delete($calendarLeave->id);
            return $isDeleted;
        }

        return false;
    }
}
