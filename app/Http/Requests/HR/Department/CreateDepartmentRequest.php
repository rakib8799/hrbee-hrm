<?php

namespace App\Http\Requests\HR\Department;

use Illuminate\Foundation\Http\FormRequest;

class CreateDepartmentRequest extends FormRequest
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
            'name' => 'required|unique:hr_departments',
            'parent_id' => 'nullable|exists:hr_departments,id'
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.department.name.required'),
            'name.unique' => __('validation.custom.department.name.unique'),
            'parent_id.exists' => __('validation.custom.department.parent_id.exists')
        ];
    }
}
