<?php

namespace App\Http\Controllers\HR\Leave;

use App\Constants\Constants;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Services\Core\HelperService;
use App\Services\HR\DepartmentService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\HR\Leave\LeaveAllocation;
use Illuminate\Support\Facades\Redirect;
use App\Services\HR\Leave\LeaveTypeService;
use App\Services\HR\Employee\EmployeeService;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\HR\Leave\LeaveAllocationService;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\HR\Leave\CreateLeaveAllocationRequest;
use App\Models\HR\Leave\LeaveType;
use App\Http\Requests\HR\Leave\UpdateLeaveAllocationRequest;

class LeaveAllocationController extends Controller implements HasMiddleware
{
    protected LeaveAllocationService $leaveAllocationService;
    protected EmployeeService $employeeService;
    protected LeaveTypeService $leaveTypeService;
    protected DepartmentService $departmentService;

    public function __construct(LeaveAllocationService $leaveAllocationService, EmployeeService $employeeService, LeaveTypeService $leaveTypeService, DepartmentService $departmentService)
    {
        $this->leaveAllocationService = $leaveAllocationService;
        $this->employeeService = $employeeService;
        $this->leaveTypeService = $leaveTypeService;
        $this->departmentService = $departmentService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('leaveAllocation.editable', only: ['edit', 'update']),
            new Middleware('permission:can-create-leave-allocation', only: ['create', 'store']),
            new Middleware('permission:can-edit-leave-allocation', only: ['edit', 'update']),
            new Middleware('permission:can-view-leave-allocation', only: ['index']),
            new Middleware('permission:can-view-details-leave-allocation', only: ['']),
            new Middleware('permission:can-approve-leave-allocation', only: ['approve', 'decline']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('leaveAllocations');
        $leaveAllocations = $this->leaveAllocationService->getLeaveAllocations();
        $requestStatuses = HelperService::getRequestStatuses();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'leaveAllocations' => $leaveAllocations,
            'requestStatuses' => $requestStatuses,
            'pageTitle' => 'Leave Allocation List'
        ];

        return Inertia::render('LeaveAllocation/Index', $responseData);
    }

    public function create(LeaveType $leaveType = null)
    {
        $breadcrumbs = Breadcrumbs::generate('addLeaveAllocation');
        $employees = $this->employeeService->getActiveEmployees();
        $departments = $this->departmentService->getDepartments();
        $leaveTypes = $this->leaveTypeService->getActiveLeaveTypes();
        $allocationTypes = HelperService::getAllocationTypes();
        $allocationModes = HelperService::getAllocationModes();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'employees' => $employees,
            'departments' => $departments,
            'leaveType' => $leaveType,
            'leaveTypes' => $leaveTypes,
            'allocationTypes' => $allocationTypes,
            'allocationModes' => $allocationModes,
            'pageTitle' => 'Add Leave Allocation'
        ];

        if(isset($leaveType)) {
            return Inertia::render('LeaveType/CreateLeaveTypeAllocation', $responseData);
        }
        return Inertia::render('LeaveAllocation/Create', $responseData);
    }

    public function store(CreateLeaveAllocationRequest $request, LeaveType $leaveType = null)
    {
        $validatedData = $request->validated();
        $leaveAllocation = $this->leaveAllocationService->createLeaveAllocation($validatedData);

        if(isset($leaveType)) {
            $status = $leaveType ? Constants::SUCCESS : Constants::ERROR;
            $message = $leaveType ? 'Leave type with allocation created successfully' : 'Leave type with allocation could not be created';
            return Redirect::route('leave-types.index')->with($status, $message);
        }

        $status = $leaveAllocation ? Constants::SUCCESS : Constants::ERROR;
        $message = $leaveAllocation ? 'Leave allocation created successfully' : 'Leave allocation could not be created';
        return Redirect::route('leave-allocations.index')->with($status, $message);
    }

    public function edit(LeaveAllocation $leaveAllocation)
    {
        $breadcrumbs = Breadcrumbs::generate('editLeaveAllocation', $leaveAllocation);
        $leaveAllocation = $this->leaveAllocationService->getLeaveAllocationDetails($leaveAllocation);
        $employees = $this->employeeService->getActiveEmployees();
        $departments = $this->departmentService->getDepartments();
        $leaveTypes = $this->leaveTypeService->getActiveLeaveTypes();
        $allocationTypes = HelperService::getAllocationTypes();
        $allocationModes = HelperService::getAllocationModes();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'leaveAllocation' => $leaveAllocation,
            'employees' => $employees,
            'departments' => $departments,
            'leaveTypes' => $leaveTypes,
            'allocationTypes' => $allocationTypes,
            'allocationModes' => $allocationModes,
            'pageTitle' => 'Edit Leave Allocation'
        ];
        return Inertia::render('LeaveAllocation/Edit', $responseData);
    }

    public function update(UpdateLeaveAllocationRequest $request, LeaveAllocation $leaveAllocation)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->leaveAllocationService->updateLeaveAllocation($leaveAllocation, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Leave allocation updated successfully' : 'Leave allocation could not be updated';
        return Redirect::route('leave-allocations.index')->with($status, $message);
    }

    public function approve(LeaveAllocation $leaveAllocation)
    {
        $leaveAllocation = $this->leaveAllocationService->approveLeaveAllocation($leaveAllocation);
        $status = $leaveAllocation ? Constants::SUCCESS : Constants::ERROR;
        $message = $leaveAllocation ? 'Leave allocation approved successfully' : 'Leave allocation could not be approved';
        return Redirect::route('leave-allocations.index')->with($status, $message);
    }

    public function decline(LeaveAllocation $leaveAllocation)
    {
        $isUpdated = $this->leaveAllocationService->declineLeaveAllocation($leaveAllocation);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Leave allocation declined successfully' : 'Leave allocation could not be declined';
        return Redirect::route('leave-allocations.index')->with($status, $message);
    }
}
