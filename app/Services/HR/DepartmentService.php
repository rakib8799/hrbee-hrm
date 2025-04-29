<?php

namespace App\Services\HR;

use App\Models\HR\Department;
use App\Models\HR\Employee;
use App\Services\Core\BaseModelService;
use Illuminate\Support\Str;

class DepartmentService extends BaseModelService
{
    public function model(): string
    {
        return Department::class;
    }

    public function getDepartments($isActive = true)
    {
        $departments = $this->model()::where('is_active', $isActive)->get();
        return $departments;
    }

    public function getParentDepartments()
    {
        $parentDepartments = $this->model()::where('is_active', 1)->where('parent_id', null)->get();
        return $parentDepartments;
    }

    public function getDepartmentEmployeeList(Department $department)
    {
        $departmentEmployees = $department->employees()->get();
        return $departmentEmployees;
    }

    public function createDepartment($validatedData)
    {
        $validatedData['slug'] = Str::slug($validatedData['name']);
        $department = $this->create($validatedData);
        return $department;
    }

    public function deleteDepartment(Department $department)
    {
        // Check if the department is referred to in other tables
        if ($this->hasReferences($department)) {
            return false;
        }
        $this->delete($department->id);
        return true;
    }

    public function changeStatus(Department $department, $isActive)
    {
        $isActive = ( $isActive == true ) ? false : true;
        $this->update($department, ['is_active' => $isActive]);
        return $department;
    }

    private function hasReferences(Department $department)
    {
        // Check for references in Department tables
        if (Department::whereNotNull('parent_id')->where('parent_id', $department->id)->exists()) {
            return true;
        }

        // Check for references in EmployeeManagement tables
        if (Employee::where('department_id', $department->id)->exists()) {
            return true;
        }
    }
}
