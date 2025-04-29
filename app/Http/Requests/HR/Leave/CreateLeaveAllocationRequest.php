<?php

namespace App\Http\Requests\HR\Leave;

use App\Constants\Constants;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveAllocationRequest extends FormRequest
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
            'name' => 'required|string',
            'leave_type_id' => 'required|exists:hr_leave_types,id',
            'holiday_type' => 'required|in:'.implode(',', [Constants::COMPANY, Constants::EMPLOYEE, Constants::DEPARTMENT]),
            'employee_id' => [
                'array',
                Rule::requiredIf(fn () => 
                    $this->holiday_type === Constants::EMPLOYEE
                ),
                'exists:hr_employees,id'
            ],
            'department_id' => [
                'nullable',
                Rule::requiredIf(fn () => 
                    $this->holiday_type === Constants::DEPARTMENT 
                ),
                'exists:hr_departments,id'
            ],
            'allocation_type' => 'required|in:' . Constants::REGULAR,
            'number_of_days' => 'required|numeric',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'notes' => 'string|nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'leave_type_id.required' => 'Leave type is required.',
            'leave_type_id.exists' => 'Selected leave type is invalid.',
            'holiday_type.required' => 'Holiday type is required.',
            'holiday_type.in' => 'Holiday type must be one of: Company, Employee, Department.',
            'employee_id.array' => 'Employee must be an array.',
            'employee_id.required' => 'Employee is required when holiday type is employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'department_id.required' => 'Department is required when holiday type is department.',
            'department_id.exists' => 'Selected department is invalid.',
            'allocation_type.required' => 'Allocation type is required.',
            'allocation_type.in' => 'Allocation type must be one of: Regular.',
            'number_of_days.required' => 'Number of days is required.',
            'number_of_days.numeric' => 'Number of days must be a number.',
            'date_from.required' => 'Start date is required.',
            'date_from.date' => 'Start date is not a valid date.',
            'date_to.required' => 'End date is required.',
            'date_to.date' => 'End date is not a valid date.',
            'date_to.after' => 'End date must be a date after the start date.',
            'notes.string' => 'Note must be a string.',
        ];
    }
}