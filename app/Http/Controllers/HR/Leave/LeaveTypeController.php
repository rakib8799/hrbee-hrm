<?php

namespace App\Http\Controllers\HR\Leave;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Leave\CreateLeaveTypeRequest;
use App\Http\Requests\HR\Leave\UpdateLeaveTypeRequest;
use App\Models\HR\Leave\LeaveType;
use App\Services\Core\HelperService;
use App\Services\HR\Leave\LeaveTypeService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class LeaveTypeController extends Controller implements HasMiddleware
{
    protected LeaveTypeService $leaveTypeService;

    public function __construct(LeaveTypeService $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-leave-type', only: ['create', 'store']),
            new Middleware('permission:can-edit-leave-type', only: ['changeStatus']),
            new Middleware('permission:can-view-leave-type', only: ['index']),
            new Middleware('permission:can-view-details-leave-type', only: ['show']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('leaveTypes');
        $leaveTypes = $this->leaveTypeService->getLeaveTypes();
        $responseData = [
            'leaveTypes' => $leaveTypes,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.leave.leaveType.index')
        ];
        return Inertia::render('LeaveType/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addLeaveType');
        $leaveTypeColors = HelperService::getLeaveTypeColors();
        $leaveValidationTypes = HelperService::getLeaveValidationTypes();
        $requestUnits = HelperService::getRequestUnits();
        $responseData = [
            'leaveTypeColors' => $leaveTypeColors,
            'leaveValidationTypes' => $leaveValidationTypes,
            'requestUnits' => $requestUnits,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.leave.leaveType.create')
        ];
        return Inertia::render('LeaveType/Create', $responseData);
    }

    public function store(CreateLeaveTypeRequest $request)
    {
        $validatedData = $request->validated();
        $leaveType = $this->leaveTypeService->create($validatedData);
        $status = $leaveType ? Constants::SUCCESS : Constants::ERROR;
        $message = $leaveType ? __('message.custom.leave.leaveType.store.success') : __('message.custom.leave.leaveType.store.error');
        return Redirect::route('leave-types.createLeaveTypeAllocation', $leaveType)->with($status, $message);
    }

    public function show(LeaveType $leaveType)
    {
        $breadcrumbs = Breadcrumbs::generate('leaveTypeDetails', $leaveType);
        $responseData = [
            'leaveType' => $leaveType,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.leave.leaveType.show')
        ];

        return Inertia::render('LeaveType/Show', $responseData);
    }

    public function leaveTypeAllocation(LeaveType $leaveType)
    {
        $breadcrumbs = Breadcrumbs::generate('leaveTypeAllocationDetails', $leaveType);
        $leaveAllocations = $this->leaveTypeService->getLeaveTypeAllocationList($leaveType);
        $responseData = [
            'leaveAllocations' => $leaveAllocations,
            'leaveType' => $leaveType,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.leave.leaveType.leaveTypeAllocationInfo.show')
        ];

        return Inertia::render('LeaveType/LeaveTypeAllocation', $responseData);
    }

    public function edit(LeaveType $leaveType)
    {
        $breadcrumbs = Breadcrumbs::generate('editLeaveType', $leaveType);
        $leaveTypeColors = HelperService::getLeaveTypeColors();
        $leaveValidationTypes = HelperService::getLeaveValidationTypes();
        $requestUnits = HelperService::getRequestUnits();
        $responseData = [
            'leaveType' => $leaveType,
            'leaveTypeColors' => $leaveTypeColors,
            'leaveValidationTypes' => $leaveValidationTypes,
            'requestUnits' => $requestUnits,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.leave.leaveType.edit')
        ];
        return Inertia::render('LeaveType/Create', $responseData);
    }

    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType)
    {
        $validatedData = $request->validated();
        $isUpdated = $leaveType->update($validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.leave.leaveType.update.success') : __('message.custom.leave.leaveType.update.error');
        return Redirect::route('leave-types.show', $leaveType)->with($status, $message);
    }

    public function changeStatus(Request $request, LeaveType $leaveType)
    {
        $leaveType = $this->leaveTypeService->changeStatus($leaveType, $request->is_active);
        $status = $leaveType ? Constants::SUCCESS : Constants::ERROR;
        $message = $leaveType ? ($leaveType->is_active ? __('message.custom.leave.leaveType.changeStatus.activate') : __('message.custom.leave.leaveType.changeStatus.deactivate')) : __('message.custom.leave.leaveType.changeStatus.error');
        return Redirect::route('leave-types.index')->with($status, $message);
    }
}
