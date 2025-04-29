<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use App\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceLogRequest extends FormRequest
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
            'staff_id' => 'required',
            'punch_type' => 'required|in:in,out,break',
            'punch_time' => 'required',
            'work_from' => 'nullable|in:'.implode(',', [Constants::WORK_FROM_HOME, Constants::WORK_FROM_OFFICE, Constants::WORK_REMOTELY])
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'staff_id.required' => __('validation.custom.employeeManagement.attendanceLog.staff_id.required'),
            'punch_type.required' => __('validation.custom.employeeManagement.attendanceLog.punch_time.required'),
            'punch_type.in' => __('validation.custom.employeeManagement.attendanceLog.punch_type.in'),
            'punch_time.required' => __('validation.custom.employeeManagement.attendanceLog.punch_time.required'),
            'work_from.in' => __('validation.custom.employeeManagement.attendanceLog.work_from.in')
        ];
    }
}
