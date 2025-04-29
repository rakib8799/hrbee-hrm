<?php

namespace App\Http\Requests\HR\EmployeeManagement;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeDocumentRequest extends FormRequest
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
            'file_path' => 'required|string',
            'employee_id' => 'required|exists:hr_employees,id',
            'source' => 'required|in:employee,office',
            'document_type_id' => 'required|exists:document_types,id',
        ];
    }

    /**
     * Vlidation Messages
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.employeeManagement.employeeDocument.name.required'),
            'file_path.required' => __('validation.custom.employeeManagement.employeeDocument.file_path.required'),
            'employee_id.required' => __('validation.custom.employeeManagement.employeeDocument.employee_id.required'),
            'employee_id.exists' => __('validation.custom.employeeManagement.employeeDocument.employee_id.exists'),
            'source.required' => __('validation.custom.employeeManagement.employeeDocument.source.required'),
            'source.in' => __('validation.custom.employeeManagement.employeeDocument.source.in'),
            'document_type_id.required' => __('validation.custom.employeeManagement.employeeDocument.document_type_id.required'),
            'document_type_id.exists' => __('validation.custom.employeeManagement.employeeDocument.document_type_id.exists')
        ];
    }
}
