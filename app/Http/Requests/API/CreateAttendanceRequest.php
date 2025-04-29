<?php

namespace App\Http\Requests\API;

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
            'staff_id' => 'required',
            'punch_time' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validatePunchTimeRange($attribute, $value, $fail);
                }
            ],
            'punch_type' => 'required|in:in,out,break,leave'
        ];
    }

    private function validatePunchTimeRange($attribute, $value, $fail)
    {
        $minTimestamp = strtotime('1970-01-01 00:00:01');
        $inputTimestamp = strtotime($value);
        if ($inputTimestamp < $minTimestamp) {
            $fail('Punch time must be a valid date/time');
        }
    }

    /**
     * Vlidation Messages
     */
    public function messages(): array
    {
        return [
            'staff_id.required' => 'The staff ID is required.',
            'punch_time.required' => 'The punch time is required.',
            'punch_type.required' => 'The punch type is required.',
            'punch_type.in' => 'The punch type must be one of: in, out, break, leave.',
        ];
    }
}
