<?php

namespace App\Http\Controllers\HR\Leave;

use App\Constants\Constants;
use Inertia\Inertia;
use App\Models\Configuration;
use App\Http\Controllers\Controller;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Services\HR\Leave\LeaveService;
use Illuminate\Support\Facades\Redirect;
use App\Services\HR\Employee\EmployeeService;
use Illuminate\Routing\Controllers\Middleware;
use App\Services\HR\Leave\CalendarLeaveService;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Http\Requests\HR\Leave\CreateLeaveRequest;
use App\Models\HR\Leave\Leave;
use App\Services\Core\HelperService;

class LeaveController extends Controller implements HasMiddleware
{
    protected LeaveService $leaveService;
    protected EmployeeService $employeeService;
    protected CalendarLeaveService $calendarLeaveService;

    public function __construct(LeaveService $leaveService, EmployeeService $employeeService, CalendarLeaveService $calendarLeaveService)
    {
        $this->leaveService = $leaveService;
        $this->employeeService = $employeeService;
        $this->calendarLeaveService = $calendarLeaveService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('role.isEmployee', only: ['employeeLeaves', 'storeEmployeeLeave', 'employeeLeaveRequests']),
            new Middleware('permission:can-view-employee-leave', only: ['employeeLeaves', 'employeeLeaveRequests']),
            new Middleware('permission:can-approve-leave', only: ['approve', 'decline']),
            new Middleware('permission:can-view-leave', only: ['leaveRequests'])
        ];
    }

    public function leaveRequests()
    {
        $breadcrumbs = Breadcrumbs::generate('leaveRequests');
        $user = auth()->user();
        $leaves = $this->leaveService->getLeaves($user);
        $requestStatuses = HelperService::getRequestStatuses();
        $isSuperAdminOrHR = HelperService::isSuperAdminOrHR($user);
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'requestStatuses' => $requestStatuses,
            'leaves' => $leaves,
            'isSuperAdminOrHR' => $isSuperAdminOrHR,
            'pageTitle' => 'Leave Request List'
        ];

        return Inertia::render('Leave/Index', $responseData);
    }

    public function employeeLeaveRequests()
    {
        $breadcrumbs = Breadcrumbs::generate('employeeLeaveRequests');
        $employee = auth()->user()->employee;
        $leaves = $this->leaveService->getEmployeeLeaveRequests($employee);
        $requestStatuses = HelperService::getRequestStatuses();
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'leaves' => $leaves,
            'requestStatuses' => $requestStatuses,
            'pageTitle' => 'Leave Request List'
        ];

        return Inertia::render('Leave/EmployeeLeaveRequest', $responseData);
    }

    public function approve(Leave $leave)
    {
        $isUpdated = $this->leaveService->approveLeave($leave);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Leave request approved successfully' : 'Leave request could not be approved';
        return Redirect::route('leaves.leaveRequests')->with($status, $message);
    }

    public function decline(Leave $leave)
    {
        $isUpdated = $this->leaveService->declineLeave($leave);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Leave request declined successfully' : 'Leave request could not be declined';
        return Redirect::route('leaves.leaveRequests')->with($status, $message);
    }

    public function employeeLeaves()
    {
        $breadcrumbs = Breadcrumbs::generate('employeeLeaves');
        $employee = auth()->user()->employee;
        $calendarLeaves = $this->calendarLeaveService->getEmployeeCalendarLeaves($employee);
        $weekendsConfig = Configuration::where('option_name', 'weekends')->first();
        $weekends = json_decode($weekendsConfig->option_value, true);
        $employeeleaveAllocations = $this->employeeService->getEmployeeLeaveAllocations($employee);
        $leaveRemainingDays = $this->employeeService->getEmployeeLeaveRemainingDays($employee, $employeeleaveAllocations);
        $responseData = [
            'calendarLeaves' => $calendarLeaves,
            'weekends' => $weekends,
            'breadcrumbs' => $breadcrumbs,
            'employeeleaveAllocations' => $employeeleaveAllocations,
            'leaveRemainingDays' => $leaveRemainingDays,
            'pageTitle' => 'Leave Management'
        ];
        return Inertia::render('Leave/EmployeeLeave', $responseData);
    }

    public function storeEmployeeLeave(CreateLeaveRequest $request)
    {
        $validatedData = $request->validated();
        $leave = $this->leaveService->createLeave($validatedData);
        $status = $leave ? Constants::SUCCESS : Constants::ERROR;
        $message = $leave ? 'Leave request created successfully' : 'Leave request could not be created';
        return Redirect::route('leaves.employeeLeaves')->with($status, $message);
    }
}
