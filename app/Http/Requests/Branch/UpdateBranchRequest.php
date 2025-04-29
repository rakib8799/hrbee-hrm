<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
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
        $branchId = $this->route('branch')->id;
        return [
            'name' => 'required|string|unique:branches,name,' . $branchId,
            'manager_id' => 'nullable|exists:hr_employees,id',
            'branch_code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string',
            'address_line_one' => 'nullable|string',
            'address_line_two' => 'nullable|string',
            'floor' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'zip_code' => 'nullable|string',
            'geo_location' => 'nullable|string',
            'day_off' => 'nullable|json',
        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [
            'name.required' => __('validation.custom.branch.name.required'),
            'name.unique' => __('validation.custom.branch.name.unique'),
            'name.string' => __('validation.custom.branch.name.string'),
            'manager_id.exists' => __('validation.custom.branch.manager_id.exists'),
            'branch_code.string' => __('validation.custom.branch.branch_code.string'),
            'branch_code.max' => __('validation.custom.branch.branch_code.max'),
            'phone_number.string' => __('validation.custom.branch.phone_number.string'),
            'address_line_one.string' => __('validation.custom.commonComponents.addressComponent.address_line_one.string'),
            'address_line_two.string' => __('validation.custom.commonComponents.addressComponent.address_line_two.string'),
            'floor.string' => __('validation.custom.commonComponents.addressComponent.floor.string'),
            'city.string' => __('validation.custom.commonComponents.addressComponent.city.string'),
            'state.string' => __('validation.custom.commonComponents.addressComponent.state.string'),
            'zip_code.string' => __('validation.custom.commonComponents.addressComponent.zip_code.string'),
            'geo_location.string' => __('validation.custom.branch.geo_location.string'),
            'day_off.json' => __('validation.custom.branch.day_off.string')
        ];
    }
}
