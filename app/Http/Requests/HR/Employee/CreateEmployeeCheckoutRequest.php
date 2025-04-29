<?php

namespace App\Http\Requests\HR\Employee;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeCheckoutRequest extends FormRequest
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
            'punch_type' => 'required|in:out',
            'punch_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePunchTimeConditions($attribute, $value, $fail);
                }
            ]
        ];
    }

    private function validatePunchTimeConditions($attribute, $value, $fail)
    {
        $user = auth()->user();
        $employee = $user->employee;

        $lastCheckIn = Carbon::parse($employee->last_check_in);

        $attendanceDate = $employee->lastAttendance->attendance_date;
        $punchTimestamp = Carbon::createFromFormat('Y-m-d H:i', $attendanceDate . ' ' . $value);

        if ($lastCheckIn->gte($punchTimestamp)) {
            $fail(__('validation.custom.employee.checkout.outGreaterThanIn'));
        }
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'punch_type.required' => __('validation.custom.employee.checkout.punch_type.required'),
            'punch_time.required' => __('validation.custom.employee.checkout.punch_time.required')
        ];
    }
}
