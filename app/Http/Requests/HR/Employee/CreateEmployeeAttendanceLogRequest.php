<?php

namespace App\Http\Requests\HR\Employee;

use App\Constants\Constants;
use Carbon\Carbon;
use App\Models\HR\Attendance;
use App\Models\HR\AttendanceLog;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeAttendanceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'punch_type' => [
                'required',
                'in:in,out,break',
                function ($attribute, $value, $fail) {
                    $this->validatePunchTypeConditions($attribute, $value, $fail);
                }
            ],
            'punch_time' => 'required',
            'work_from' => [
                function ($attribute, $value, $fail) {
                    $this->validateWorkFrom($attribute, $value, $fail);
                },
            ],
            'note' => 'nullable|string'
        ];
    }

    private function validatePunchTypeConditions($attribute, $value, $fail)
    {
        $punchTimestamp = Carbon::parse($this->input('punch_time'));
        $punchDate = $punchTimestamp->format('Y-m-d');
        $user = auth()->user();
        $employee = $user->employee;

        $lastAttendanceId = $employee->last_attendance_id;
        $lastCheckOut = $employee->last_check_out;

        if ($employee->joining_date == null) {
            $fail(__('validation.custom.employee.employeeNotJoinedYet'));
        }
        else if ($lastAttendanceId === null) {
            if ($value === Constants::BREAK || $value === Constants::OUT) {
                $fail(__('validation.custom.employee.attendanceLog.punchBeforeIn'));
            }
        } else {
            $lastAttendanceDate = $employee->lastAttendance->attendance_date;
            $lastAttendanceLog = AttendanceLog::where('employee_id', $employee->id)
                ->orderBy('id', 'desc')
                ->first();

            if ($lastCheckOut === null) {
                if ($punchDate === $lastAttendanceDate) {
                    $this->validatePunchSequence($value, $lastAttendanceLog, $punchTimestamp, $fail);
                }
            } else {
                if ($punchDate !== $lastAttendanceDate) {
                    if ($value === Constants::BREAK || $value === Constants::OUT) {
                        $fail(__('validation.custom.employee.attendanceLog.punchBeforeIn'));
                    } 
                    
                    $isDuplicateDate = Attendance::where('employee_id', $employee->id)->where('attendance_date', $punchDate)->first();
                    
                    if ($isDuplicateDate) {
                        $fail(__('validation.custom.employee.attendanceLog.isDuplicateDate'));
                    }
                } else {
                    $this->validatePunchSequence($value, $lastAttendanceLog, $punchTimestamp, $fail);
                }
            }
        }
    }

    private function validatePunchSequence($value, $lastAttendanceLog, $punchTimestamp, $fail)
    {
        $lastPunchTime = Carbon::parse($lastAttendanceLog->punch_time);
        if ($lastPunchTime->gte($punchTimestamp)) {
            $fail(__('validation.custom.employee.attendanceLog.punchTimeOutOfSequence'));
        }

        if ($value === Constants::IN && $lastAttendanceLog->punch_type === Constants::IN) {
            $fail(__('validation.custom.employee.attendanceLog.punchInWithoutBreak'));
        } else if ($value === Constants::BREAK && $lastAttendanceLog->punch_type === Constants::BREAK) {
            $fail(__('validation.custom.employee.attendanceLog.punchAfterBreak'));
        }
    }

    private function validateWorkFrom($attribute, $value, $fail)
    {
        $punchTimestamp = Carbon::parse($this->input('punch_time'));
        $punchDate = $punchTimestamp->format('Y-m-d');

        $user = auth()->user();
        $attendance = Attendance::where('employee_id', $user->employee->id)
            ->where('attendance_date', $punchDate)
            ->first();

        if (!$attendance && !$value) {
            $fail(__('validation.custom.employee.attendanceLog.workFromFirstIn'));
        } else if ($attendance && $value) {
            $fail(__('validation.custom.employee.attendanceLog.alreadyEnteredWorkFrom'));
        } else if ($value && !in_array($value, [Constants::WORK_FROM_HOME, Constants::WORK_FROM_OFFICE, Constants::WORK_REMOTELY])) {
            $fail(__('validation.custom.employee.attendanceLog.workFromType'));
        }
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'punch_type.required' => __('validation.custom.employee.attendanceLog.punch_type.required'),
            'punch_type.in' => __('validation.custom.employee.attendanceLog.punch_type.in'),
            'punch_time.required' => __('validation.custom.employee.attendanceLog.punch_time.required'),
            'note.string' => __('validation.custom.employee.attendanceLog.note.string')
        ];
    }
}