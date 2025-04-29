<?php

namespace App\Services\HR\Employee;

use Carbon\Carbon;
use App\Models\HR\Employee;
use App\Constants\Constants;
use App\Models\HR\Attendance;
use App\Models\HR\AttendanceLog;
use Illuminate\Support\Facades\DB;
use App\Services\Core\HelperService;
use App\Services\Core\BaseModelService;
use App\Services\HR\Employee\AttendanceService;

class AttendanceLogService extends BaseModelService
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function model(): string
    {
        return AttendanceLog::class;
    }

    public function getAttendanceLogs(Employee $employee)
    {
        $attendances = $this->model()::where('employee_id', $employee->id)->orderBy('punch_time', 'desc')->get();
        return $attendances->count() > 0 ? $attendances : false;
    }

    /**
     * For Mobile App API
     */
    public function createAttendanceLog($validatedData)
    {
        $result = DB::transaction(function () use ($validatedData) {
            $staffId = $validatedData['staff_id'];
            $employee = Employee::where('staff_id', $staffId)->first();
            if ($employee) {
                $validatedData['employee_id'] = $employee->id;
                $this->create($validatedData);
            }
            return $employee;
        });
        return $result;
    }

    /**
     * Get logged in employee attendance logs
     */
    public function getEmployeeAttendanceLogs(Employee $employee)
    {
        return $this->getAttendanceLogs($employee);
    }

    public function canPunch(Employee $employee)
    {
        $lastAttendanceLog = $this->model()::where('employee_id', $employee->id)
            ->orderBy('id', 'desc')
            ->first();

        $canPunch = [
            'in' => $this->canPunchIn($lastAttendanceLog),
            'break' => $this->canPunchBreakOrOut($lastAttendanceLog),
            'out' => $this->canPunchBreakOrOut($lastAttendanceLog)
        ];

        return $canPunch;
    }

    private function canPunchIn($lastAttendanceLog = null)
    {
        return (!$lastAttendanceLog) || ($lastAttendanceLog && $lastAttendanceLog->punch_type !== Constants::IN);
    }

    private function canPunchBreakOrOut($lastAttendanceLog)
    {
        return $lastAttendanceLog && $lastAttendanceLog->punch_type === Constants::IN;
    }

    public function createEmployeeAttendanceLog(Employee $employee, $validatedData)
    {
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $punchType = $validatedData['punch_type'];
            $punchTimestamp = Carbon::parse($validatedData['punch_time']);
            $punchDate = $punchTimestamp->toDateString();
            $validatedData['employee_id'] = $employee->id;
            $attendanceLog = $this->create($validatedData);

            $employeeAttendance = $employee->lastAttendance;
            if ($punchType === Constants::IN && $employee->last_check_out && $employeeAttendance->attendance_date === $punchDate) {
                $employee->last_check_out = null;
                $employee->save();
            } else if ($punchType === Constants::IN && ($employee->last_attendance_id === null || ($employeeAttendance && $employeeAttendance->attendance_date !== $punchDate))) {
                $attendanceData = [
                    'employee_id' => $employee->id,
                    'attendance_date' => $punchDate,
                    'check_in' => $punchTimestamp,
                    'note' => $validatedData['note'],
                    'work_from' => $validatedData['work_from']
                ];
                $attendance = $this->attendanceService->create($attendanceData);

                $employee->update([
                    'last_attendance_id' => $attendance->id,
                    'last_check_in' => $punchTimestamp,
                    'last_check_out' => null
                ]);
            } elseif ($punchType === Constants::OUT && $employee->last_check_in) {
                $employee->update(['last_check_out' => $punchTimestamp]);
                $workedHours = HelperService::workedHoursCalculation($employee->id, $employee->last_check_in, $punchTimestamp);
                $formatWoredHours = HelperService::formatTotalMinutes($workedHours);
                $effectiveHours = HelperService::effectiveHoursCalculation($employee->id, $employee->last_check_in, $punchTimestamp);
                $formatEffectiveHours = HelperService::formatTotalMinutes($effectiveHours);

                $employee->lastAttendance->update([
                    'check_out' => $punchTimestamp,
                    'worked_hours' => $formatWoredHours,
                    'effective_hours' => $formatEffectiveHours
                ]);
            }
            return $attendanceLog;
        });
        return $result;
    }

    public function hasPendingCheckout(Employee $employee)
    {
        if ($employee) {
            $lastEmployeeAttendance = $employee->last_attendance_id;
            $lastEmployeeCheckin = Carbon::parse($employee->last_check_in);
            $lastEmployeeCheckout = $employee->last_check_out;
            $today = Carbon::today();
            return $lastEmployeeAttendance && $lastEmployeeCheckout === null && !$lastEmployeeCheckin->isSameDay($today);
        }
        return false;
    }

    public function createEmployeeCheckout(Employee $employee, $validatedData)
    {
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $punchType = $validatedData['punch_type'];
            $punchTime = $validatedData['punch_time'];

            $validatedData['employee_id'] = $employee->id;
            $attendanceDate = $employee->lastAttendance->attendance_date;
            $validatedData['punch_time'] = Carbon::createFromFormat('Y-m-d H:i', $attendanceDate . ' ' . $punchTime);
            $attendanceLog = $this->create($validatedData);

            $punchTimestamp = Carbon::parse($validatedData['punch_time']);
            if($punchType === Constants::OUT && $employee->last_check_in) {
                $employee->update(['last_check_out' => $punchTimestamp]);
                $workedHours = HelperService::workedHoursCalculation($employee->id, $employee->last_check_in, $punchTimestamp);
                $formatWoredHours = HelperService::formatTotalMinutes($workedHours);
                $effectiveHours = HelperService::effectiveHoursCalculation($employee->id, $employee->last_check_in, $punchTimestamp);
                $formatEffectiveHours = HelperService::formatTotalMinutes($effectiveHours);

                $employee->lastAttendance->update([
                    'check_out' => $punchTimestamp,
                    'worked_hours' => $formatWoredHours,
                    'effective_hours' => $formatEffectiveHours
                ]);
            }
            return $attendanceLog;
        });
        return $result;
    }

    public function isFirstPunchOfDay(Employee $employee)
    {
        $currentDate = Carbon::now()->toDateString();
        $attendance = Attendance::where(['employee_id' => $employee->id, 'attendance_date' => $currentDate])->first();
        if ($attendance) {
            return false;
        }
        return true;
    }
}
