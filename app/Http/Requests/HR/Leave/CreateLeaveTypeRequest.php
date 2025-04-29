<?php

namespace App\Http\Requests\HR\Leave;

use App\Constants\Constants;
use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveTypeRequest extends FormRequest
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
            'name' => 'required|string|unique:hr_leave_types',
            'color' => 'required|string|unique:hr_leave_types',
            'sequence' => 'required|numeric|unique:hr_leave_types',
            'leave_validation_type' => 'required|in:'.implode(',', [Constants::HR, Constants::MANAGER, Constants::BOTH, Constants::NO_VALIDATIONS]),
            'request_unit' => 'required|in:'.implode(',', [Constants::DAY, Constants::HOUR])
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.leave.leaveType.name.required'),
            'name.string' => __('validation.custom.leave.leaveType.name.string'),
            'name.unique' => __('validation.custom.leave.leaveType.name.unique'),
            'color.required' => __('validation.custom.leave.leaveType.color.required'),
            'color.string' => __('validation.custom.leave.leaveType.color.string'),
            'color.unique' => __('validation.custom.leave.leaveType.color.unique'),
            'sequence.required' => __('validation.custom.leave.leaveType.sequence.required'),
            'sequence.numeric' => __('validation.custom.leave.leaveType.sequence.numeric'),
            'sequence.unique' => __('validation.custom.leave.leaveType.sequence.unique'),
            'leave_validation_type.required' => __('validation.custom.leave.leaveType.leave_validation_type.required'),
            'leave_validation_type.in' => __('validation.custom.leave.leaveType.leave_validation_type.in'),
            'request_unit.required' => __('validation.custom.leave.leaveType.request_unit.required'),
            'request_unit.in' => __('validation.custom.leave.leaveType.request_unit.in')
        ];
    }
}
