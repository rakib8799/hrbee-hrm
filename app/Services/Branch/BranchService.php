<?php

namespace App\Services\Branch;

use App\Constants\Constants;
use App\Models\Branch\Branch;
use App\Models\HR\Employee;
use App\Models\User;
use App\Services\Core\BaseModelService;
use App\Services\HR\Employee\EmployeeService;
use Illuminate\Support\Facades\DB;

class BranchService extends BaseModelService
{
    protected EmployeeService $employeeService;
    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    public function model(): string
    {
        return Branch::class;
    }

    public function getBranches(User $user, $isActive = false)
    {
        $branches = '';
        if($user->hasRole([Constants::ROLE_SUPER_ADMIN, Constants::ROLE_HR])) {
            $branches = $isActive ? $this->model()::where('is_active', true)->get() : $this->model()::all();
        } else {
            $branches = $this->model()::where('manager_id', $user->employee->id)->get();
        }
        return $branches;
    }

    public function getBranchEmployeeList(Branch $branch)
    {
        $branchEmployees = $branch->employees()->get();
        return $branchEmployees;
    }

    public function createBranch($validatedData)
    {
        $result = DB::transaction(function () use ($validatedData) {
            $branch = $this->create($validatedData);
            if ($branch) {
                $this->updateBranchOfEmployee($branch, $validatedData);
            }
            return $branch;
        });
        return $result;
    }

    public function updateBranch(Branch $branch, $validatedData)
    {
        $result = DB::transaction(function () use ($branch, $validatedData) {
            $branchUpdate = $this->update($branch, $validatedData);
            if ($branchUpdate) {
                $this->updateBranchOfEmployee($branch, $validatedData);
            }
            return true;
        });
        return $result ? true : false;
    }

    private function updateBranchOfEmployee(Branch $branch, $validatedData)
    {
        $validatedData['branch_id'] = $branch->id;
        $managerId = $validatedData['manager_id'];
        $branchId = $validatedData['branch_id'];
        $employee = $this->employeeService->find($managerId);
        if($employee)
        {
            $this->employeeService->updateEmployee($employee, ['branch_id' => $branchId, 'manager_id' => $managerId]);
        }
    }

    public function changeStatus(Branch $branch, $isActive)
    {
        $isActive = ( $isActive == true ) ? false : true;
        $this->update($branch, ['is_active' => $isActive]);
        return $branch;
    }

    public function deleteBranch(Branch $branch)
    {
        // Check if the branch is referred to in other tables
        if ($this->hasReferences($branch)) {
            return false;
        }
        $this->delete($branch->id);
        return true;
    }

    private function hasReferences(Branch $branch)
    {
        // Check for references in Employee tables
        if (Employee::where('branch_id', $branch->id)->exists()) {
            return true;
        }
    }
}
