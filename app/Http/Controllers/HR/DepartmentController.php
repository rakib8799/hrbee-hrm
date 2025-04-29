<?php

namespace App\Http\Controllers\HR;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Department\CreateDepartmentRequest;
use App\Http\Requests\HR\Department\UpdateDepartmentRequest;
use App\Models\HR\Department;
use App\Services\HR\DepartmentService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class DepartmentController extends Controller implements HasMiddleware
{
    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-department', only: ['create', 'store']),
            new Middleware('permission:can-edit-department', only: ['edit', 'update', 'changeStatus']),
            new Middleware('permission:can-delete-department', only: ['destroy']),
            new Middleware('permission:can-view-department', only: ['index']),
            new Middleware('permission:can-view-details-department', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('departments');
        $departments = $this->departmentService->all();
        $parentDepartments = $this->departmentService->getParentDepartments();
        $responseData = [
            'departments' => $departments,
            'parentDepartments' => $parentDepartments,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.department.index'),
        ];
        return Inertia::render('Department/Index', $responseData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addDepartment');
        $parentDepartments = $this->departmentService->getParentDepartments();
        $responseData = [
            'parentDepartments' => $parentDepartments,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.department.create'),
        ];
        return Inertia::render('Department/Create', $responseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateDepartmentRequest $request)
    {
        $validatedData = $request->validated();
        $department = $this->departmentService->createDepartment($validatedData);
        $status = $department ? Constants::SUCCESS : Constants::ERROR;
        $message = $department ? __('message.custom.department.store.success') : __('message.custom.department.store.error');
        return Redirect::route('departments.index')->with($status, $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $breadcrumbs = Breadcrumbs::generate('departmentDetails', $department);
        $employees = $this->departmentService->getDepartmentEmployeeList($department);
        $parentDepartments = $this->departmentService->getParentDepartments();
        $responseData = [
            'employees' => $employees,
            'parentDepartments' => $parentDepartments,
            'department' => $department,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.department.show'),
        ];
        return Inertia::render('Department/Show', $responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $breadcrumbs = Breadcrumbs::generate('editDepartment', $department);
        $parentDepartments = $this->departmentService->getParentDepartments();
        $responseData = [
            'parentDepartments' => $parentDepartments,
            'department' => $department,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.department.edit'),
        ];
        return Inertia::render('Department/Create', $responseData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        $validatedData = $request->validated();
        $department = $this->departmentService->update($department, $validatedData);
        $status = $department ? Constants::SUCCESS : Constants::ERROR;
        $message = $department ? __('message.custom.department.update.success') : __('message.custom.department.error');
        return Redirect::route('departments.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $isDeleted = $this->departmentService->deleteDepartment($department);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? __('message.custom.department.destroy.success') : __('message.custom.department.destroy.error');
        return Redirect::back()->with($status, $message);
    }

    /**
     * Change Employee Status
     */
    public function changeStatus(Request $request, Department $department)
    {
        $department = $this->departmentService->changeStatus($department, $request->is_active);
        $status = "success";
        $message = $department->is_active ? __('message.custom.department.changeStatus.activate') : __('message.custom.department.changeStatus.deactivate');
        return Redirect::back()->with($status, $message);
    }
}
