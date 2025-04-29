<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\Branch\CreateBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Models\Branch\Branch;
use App\Services\Branch\BranchService;
use App\Services\HR\Employee\EmployeeService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class BranchController extends Controller implements HasMiddleware
{
    protected BranchService $branchService;
    protected EmployeeService $employeeService;

    public function __construct(BranchService $branchService, EmployeeService $employeeService)
    {
        $this->branchService = $branchService;
        $this->employeeService = $employeeService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-branch', only: ['create', 'store', 'storeBranch']),
            new Middleware('permission:can-edit-branch', only: ['edit', 'update']),
            new Middleware('permission:can-delete-branch', only: ['destroy']),
            new Middleware('permission:can-view-branch', only: ['index', 'getBranches']),
            new Middleware('permission:can-view-details-branch', only: ['show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $breadcrumbs = Breadcrumbs::generate('branches');
        $user = auth()->user();
        $branches = $this->branchService->getBranches($user);
        $employees = $this->employeeService->getActiveEmployees();
        $responseData = [
            'employees' => $employees,
            'branches' => $branches,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.branch.index'),
        ];
        return Inertia::render('Branch/Index', $responseData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addBranch');
        $employees = $this->employeeService->getActiveEmployees();
        $responseData = [
            'employees' => $employees,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.branch.create'),
        ];
        return Inertia::render('Branch/Create', $responseData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBranchRequest $request)
    {
        $validatedData = $request->validated();
        $branch = $this->branchService->createBranch($validatedData);
        $status = $branch ? Constants::SUCCESS : Constants::ERROR;
        $message = $branch ? __('message.custom.branch.store.success') : __('message.custom.branch.store.error');
        return Redirect::route('branches.index')->with($status, $message);
    }

    public function getBranches()
    {
        $user = auth()->user();
        $branches = $this->branchService->getBranches($user);
        $response = [
            'success' => true,
            'message' => 'Get Branch List',
            'data' => [
                'branches' => $branches
            ]
        ];
        $httpStatus = 200;
        return response()->json($response, $httpStatus);
    }

    public function storeBranch(CreateBranchRequest $request)
    {
        $validatedData = $request->validated();
        $this->branchService->create($validatedData);
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        $breadcrumbs = Breadcrumbs::generate('branchDetails', $branch);
        $branchEmployees = $this->branchService->getBranchEmployeeList($branch);
        $responseData = [
            'employees' => $branchEmployees,
            'branch' => $branch,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.branch.show'),
        ];
        return Inertia::render('Branch/Show', $responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $breadcrumbs = Breadcrumbs::generate('editBranch', $branch);
        $employees = $this->employeeService->getEmployees();
        $responseData = [
            'branch' => $branch,
            'employees' => $employees,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.branch.edit'),
        ];
        return Inertia::render('Branch/Create', $responseData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBranchRequest $request, Branch $branch)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->branchService->updateBranch($branch, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.branch.update.success') : __('message.custom.branch.error');
        return Redirect::route('branches.index')->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $isDeleted = $this->branchService->deleteBranch($branch);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? __('message.custom.branch.destroy.success') : __('message.custom.branch.destroy.error');
        return Redirect::back()->with($status, $message);
    }

     /**
     * Get Employee Options By Branch
     */
    public function getEmployeeOptionsByBranch(Branch $branch)
    {
        $employeeOptions = $this->employeeService->getEmployeesByBranch($branch);
        $managerId = $branch->manager_id;
        $response = [
            'success' => true,
            'message' => __('message.custom.branch.employeeOptions'),
            'data' => [
                'employeeOptions' => $employeeOptions,
                'managerId' => $managerId
            ]
        ];
        $httpStatus = 200;
        return response()->json($response, $httpStatus);
    }

    /**
     * Change Employee Status
     */
    public function changeStatus(Request $request, Branch $branch)
    {
        $branch = $this->branchService->changeStatus($branch, $request->is_active);
        $status = "success";
        $message = $branch->is_active ? __('message.custom.branch.changeStatus.activate') : __('message.custom.branch.changeStatus.deactivate');
        return Redirect::back()->with($status, $message);
    }
}
