<?php

namespace App\Services\HR\Leave;

use App\Models\HR\Leave\LeaveType;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;

class LeaveTypeService extends BaseModelService
{
    public function model(): string
    {
        return LeaveType::class;
    }

    public function changeStatus(LeaveType $leaveType, $isActive)
    {
        $isActive = ( $isActive == true ) ? false : true;
        $this->update($leaveType, ['is_active' => $isActive]);
        return $leaveType;
    }

    public function getLeaveTypes()
    {
        $leaveTypes = $this->model()::all();

        return collect($leaveTypes)->map(function ($leaveType) {
            $leaveType->leave_validation_text = HelperService::getLeaveValidationType($leaveType->leave_validation_type);
    
            return $leaveType;
        });
    }

    public function getActiveLeaveTypes()
    {
        return $this->model()::where('is_active', 1)->get();
    }

    public function getLeaveTypeAllocationList(LeaveType $leaveType)
    {
        return $leaveType->leaveAllocation()->with('employee')->get();
    }
}
