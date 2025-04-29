<?php

namespace App\Services\HR\Leave;

use Illuminate\Support\Facades\DB;
use App\Services\HR\DepartmentService;
use App\Services\Core\BaseModelService;
use App\Models\HR\Leave\LeaveAllocation;
use App\Services\HR\Employee\EmployeeService;
use App\Constants\Constants;

class LeaveAllocationService extends BaseModelService
{
    protected EmployeeService $employeeService;
    protected DepartmentService $departmentService;

    public function __construct(EmployeeService $employeeService, DepartmentService $departmentService)
    {
        $this->employeeService = $employeeService;
        $this->departmentService = $departmentService;
    }

    public function model(): string
    {
        return LeaveAllocation::class;
    }

    public function getLeaveAllocations()
    {
        return $this->model()::with(['employee', 'leaveType', 'department'])->get();
    }

    public function createLeaveAllocation($validatedData)
    {
        $result = DB::transaction(function () use ($validatedData) {
            $validatedData['status'] = Constants::PENDING;

            if ($validatedData['holiday_type'] === Constants::EMPLOYEE) {
                foreach ($validatedData['employee_id'] as $employeeId) {
                    $singleValidatedData = array_merge($validatedData, ['employee_id'=> $employeeId]);
                    $leaveAllocation = $this->create($singleValidatedData);
                }
            } else {
                $validatedData['employee_id'] = NULL;
                $leaveAllocation = $this->create($validatedData);
            }

            return $leaveAllocation;
        });
        return $result;
    }

    public function updateLeaveAllocation(leaveAllocation $leaveAllocation, $validatedData)
    {
        $validatedData['status'] = Constants::PENDING;
        $previousHolidayType = $leaveAllocation->holiday_type;
        $currentHolidayType = $validatedData['holiday_type'];

        if ($previousHolidayType === Constants::EMPLOYEE) {
            return $this->updateeEmployeeTypeLeaveAllocation($leaveAllocation, $validatedData);
        } else if ($previousHolidayType === Constants::COMPANY || $previousHolidayType === Constants::DEPARTMENT) {
            return $this->updateCompanyOrDepartmentTypeLeaveAllocation($leaveAllocation, $validatedData, $currentHolidayType);
        }
    }

    /**
     * If previous holiday_type = employee, then no changes in holiday_type and in employee_id
     */
    private function updateeEmployeeTypeLeaveAllocation(LeaveAllocation $leaveAllocation, $validatedData)
    {
        $validatedData['employee_id'] = $validatedData['employee_id'][0];
        return $this->update($leaveAllocation, $validatedData);
    }

     /**
     * If previous holiday_type = comapany or department, and current holiday_type = comapny or department then only the current data will be updated.
     * If previous holiday_type = company or department and current holiday_type = employee then current data will be updated for the first employee and new data will be created for other selected employees. 
     */
    private function updateCompanyOrDepartmentTypeLeaveAllocation(LeaveAllocation $leaveAllocation, $validatedData, $currentHolidayType)
    {
        if ($currentHolidayType === Constants::COMPANY || $currentHolidayType === Constants::DEPARTMENT) {
            $validatedData['employee_id'] = NULL;
            return $this->update($leaveAllocation, $validatedData);
        } elseif ($currentHolidayType === Constants::EMPLOYEE) {
            return $this->updateAndCreateEmployeeLeaveAllocation($leaveAllocation, $validatedData);
        }
    }

    /**
     * Updates the existing data, set employee_id and creates new data for the rest of the selected employees.
     */
    private function updateAndCreateEmployeeLeaveAllocation(LeaveAllocation $leaveAllocation, $validatedData)
    {
        $result = DB::transaction(function () use ($leaveAllocation, $validatedData) {
            $employeeIds = $validatedData['employee_id'];
            $validatedData['employee_id'] = $employeeIds[0];
            $this->update($leaveAllocation, $validatedData);

            foreach (array_slice($employeeIds, 1) as $employeeId) {
                $singleValidatedData = array_merge($validatedData, ['employee_id'=> $employeeId]);
                $this->create($singleValidatedData);
            }
            return $leaveAllocation;
        });
        return $result;
    }

    public function getLeaveAllocationDetails(LeaveAllocation $leaveAllocation)
    {
        return $leaveAllocation->load(['employee', 'leaveType', 'department']);
    }

    public function approveLeaveAllocation(LeaveAllocation $leaveAllocation)
    {
        $data['status'] = Constants::APPROVED;
        $data['approver_id'] = auth()->id();
        
        if ($leaveAllocation->holiday_type === Constants::COMPANY) {
            $employees = $this->employeeService->getActiveEmployees();
            return $this->createEmployeeLeaveAllocation($leaveAllocation, $employees, $data);
        } else if ($leaveAllocation->holiday_type === Constants::DEPARTMENT) {
            $department = $leaveAllocation->department;
            $employees = $this->departmentService->getDepartmentEmployeeList($department);
            return $this->createEmployeeLeaveAllocation($leaveAllocation, $employees, $data);
        } else if ($leaveAllocation->holiday_type === Constants::EMPLOYEE) {
            return $this->update($leaveAllocation, $data);
        }
    }

    /**
     * Update current leave allocation
     * Create new leave allocations for the employees of company or department.
     */
    public function createEmployeeLeaveAllocation(LeaveAllocation $leaveAllocation, $employees, $data)
    {
        $result = DB::transaction(function() use ($leaveAllocation, $employees, $data) {
            $this->update($leaveAllocation, $data);
            foreach($employees as $employee) {
                $data['name'] = $leaveAllocation->name;
                $data['number_of_days'] = $leaveAllocation->number_of_days;
                $data['leave_type_id'] = $leaveAllocation->leave_type_id;
                $data['employee_id'] = $employee->id;
                $data['manager_id'] = $leaveAllocation->manager_id;
                $data['parent_id'] = $leaveAllocation->id;
                $data['holiday_type'] = Constants::EMPLOYEE;
                $data['allocation_type'] = $leaveAllocation->allocation_type;
                $data['date_from'] = $leaveAllocation->date_from;
                $data['date_to'] = $leaveAllocation->date_to;
                $data['notes'] = $leaveAllocation->notes;
                $data['is_active'] = $leaveAllocation->is_active;
                $this->create($data);
            }
            return $leaveAllocation;
        });
        return $result;
    }

    public function declineLeaveAllocation(LeaveAllocation $leaveAllocation)
    {
        $data['status'] = Constants::DECLINED;
        $data['is_active'] = false;
        return $this->update($leaveAllocation, $data);
    }
}
