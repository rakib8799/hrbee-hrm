<?php

namespace App\Http\Requests\HR\Leave;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicHolidayRequest extends FormRequest
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
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'date_from.required' => 'Start date is required',
            'date_from.date' => 'Start date must be a valid date',
            'date_to.required' => 'End date is required',
            'date_to.date' => 'End date must be a valid date',
            'date_to.after_or_equal' => 'End date must be after or equal to start date'
        ];
    }
}
