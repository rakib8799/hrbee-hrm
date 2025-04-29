<?php

namespace App\Http\Requests\HR\Leave;

use App\Constants\Constants;
use Carbon\Carbon;
use App\Models\HR\Leave\Leave;
use App\Models\HR\Leave\LeaveAllocation;
use Illuminate\Foundation\Http\FormRequest;

class CreateLeaveRequest extends FormRequest
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
            'name' => 'required',
            'leave_type_id' => [
                'required',
                function ($attribute, $value, $fail) {
                    $this->validateLeaveType($attribute, $value, $fail);
                }
            ],
            'request_date_from' => 'required|date',
            'request_date_to' => [
                'required',
                'date',
                'after_or_equal:request_date_from',
                function ($attribute, $value, $fail) {
                    $this->validateLeaveRequest($attribute, $value, $fail);
                }
            ],
            'note' => 'nullable|string',
        ];
    }

    /**
     * Check whether leave allocation for selected leave type exists or not
     * We can not select leaveAllocations that does not fall in the validity range.
     */
    private function validateLeaveType($attribute, $value, $fail)
    {
        $employeeId = auth()->user()->employee->id;
        $dateFrom = $this->date_from;
        $dateTo = $this->date_to;
        $leaveAllocation = LeaveAllocation::where('leave_type_id', $value)
        ->where('employee_id', $employeeId)
        ->where('status', Constants::APPROVED)
        ->whereBetween('date_from', [$dateFrom, $dateTo])
        ->WhereBetween('date_to', [$dateFrom, $dateTo])
        ->exists();

        if (!$leaveAllocation) {
            $fail('Leave Type does not exist');
        }
    }

    /**
     * First we select total_number of leaves that have been used of the selected leave allocation.
     * If the remaining days is less than total number of requested days then show error.
     * The requested date range must be in the range of selected leave allocation.
     * Finally check whether the current requested leave dates already exists or not
     */
    private function validateLeaveRequest($attribute, $value, $fail)
    {
        $dateFrom = Carbon::parse($this->date_from);
        $dateTo = Carbon::parse($this->date_to);
        $totalAllocatedDays = $this->number_of_days;
        $leaveTypeId = $this->leave_type_id;
        $employeeId = auth()->user()->employee->id;

        $totalDaysUsed = Leave::where('employee_id', $employeeId)
        ->where('leave_type_id', $leaveTypeId)
        ->whereBetween('date_from', [$dateFrom, $dateTo])
        ->WhereBetween('date_to', [$dateFrom, $dateTo])
        ->where('status', Constants::APPROVED)
        ->sum('number_of_days');

        $totalRemainingDays = $totalAllocatedDays - $totalDaysUsed;
        $requestDateFrom = Carbon::parse($this->request_date_from);
        $requestDateTo = Carbon::parse($this->request_date_to);
        $totalDaysInRange = $requestDateFrom->diffInDays($requestDateTo) + 1;

        if ($totalRemainingDays < $totalDaysInRange) {
            $fail('You do not have enough leave allocation for the selected leave');
        } else if(!$requestDateFrom->between($dateFrom, $dateTo) || !$requestDateTo->between($dateFrom, $dateTo)) {
            $fail('Selected date range is invalid');
        }

        // Check whether the current requested leave dates already exists or not.
        $leave = Leave::where('employee_id', $employeeId)
        ->whereBetween('date_from', [$requestDateFrom, $requestDateTo])
        ->OrWhereBetween('date_to', [$requestDateFrom, $requestDateTo])
        ->exists();
        if ($leave) {
            $fail('You have already requested for some dates');
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Description field is required.',
            'leave_type_id.required' => 'Leave type field is required.',
            'request_date_from.required' => 'Start date is required.',
            'request_date_from.date' => 'start date must be a valid date.',
            'request_date_to.required' => 'End date is required.',
            'request_date_to.date' => 'End date must be a valid date.',
            'request_date_to.after_or_equal' => 'End date must be a date after or equal to the start date.',
            'note.string' => 'The note must be a string.',
        ];
    }
}
