<?php

namespace App\Http\Requests\HR\Employee;

use App\Constants\Constants;
use App\Models\HR\Attendance;
use App\Models\HR\AttendanceRequest;
use App\Models\HR\Employee;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateAttendanceRequest extends FormRequest
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
            'attendance_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateAttendanceDateAndConditions($attribute, $value, $fail);
                }
            ],
            'check_in' => 'required',
            'check_out' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateAttendanceTimesAndConditions($attribute, $value, $fail);
                }
            ],
            'work_from' => 'required|in:'.implode(',', [Constants::WORK_FROM_HOME, Constants::WORK_FROM_OFFICE, Constants::WORK_REMOTELY])
        ];
    }

    private function validateAttendanceDateAndConditions($attribute, $value, $fail)
    {
        $attendanceDate = $value;

        /**
         * Attendance already exists or not in attendance table
         */
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();
        $attendance = Attendance::where(['employee_id' => $employee->id, 'attendance_date' => $attendanceDate])->first();

        if ($attendance) {
            $fail(__('validation.custom.employee.missingAttendance.attendanceAlreadyExists') . $attendanceDate);
        }

        /**
         * Missing attendance cannot be added for:
         * - Employees without a joining date
         * - Dates before the employee's joining date
         * - Toady and Future dates
         */
        $currentDate = Carbon::now()->toDateString();
        if ($employee->joining_date == null) {
            $fail(__('validation.custom.employee.employeeNotJoinedYet'));
        } else if ($employee->joining_date == null || $employee->joining_date > $attendanceDate) {
            $fail(__('validation.custom.employee.missingAttendance.missingAttendanceNotRecorded'));
        } else if ($attendanceDate >= $currentDate) {
            $fail(__('validation.custom.employee.missingAttendance.missingAttendanceCanNotAdded'));
        }

        /**
         * Check same reuest is being created or not
         */
        $attendanceRequest = AttendanceRequest::where(['employee_id' => $employee->id, 'attendance_date' => $attendanceDate])->first();
        if($attendanceRequest) {
            $fail(__('validation.custom.employee.missingAttendance.alreadyCreatedMissingAttendance') . $attendanceDate);
        }
    }

    private function validateAttendanceTimesAndConditions($attribute, $value, $fail)
    {
        $checkInTimeStamp = Carbon::parse($this->input('check_in'));
        $checkOutTimeStamp = Carbon::parse($value);

        /**
         * Check in and out time must be greater
         */
        if ($checkInTimeStamp->greaterThanOrEqualTo($checkOutTimeStamp)) {
            $fail(__('validation.custom.employee.missingAttendance.outLaterThanIn'));
        }
    }

    /**
     * Validation messages
     */

    public function messages(): array
    {
        return [
            'attendance_date.required' => __('validation.custom.employee.missingAttendance.attendance_date.required'),
            'check_in.required' => __('validation.custom.employee.missingAttendance.check_in.required'),
            'check_out.required' => __('validation.custom.employee.missingAttendance.check_out.required'),
            'work_from.required' => __('validation.custom.employee.missingAttendance.work_from.required'),
            'work_from.in' => __('validation.custom.employee.missingAttendance.work_from.in'),
        ];
    }
}
