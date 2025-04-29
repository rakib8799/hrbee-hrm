<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Constants\Constants;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\HR\AttendanceRequest;
use App\Models\User;
use App\Services\Core\HelperService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\HR\Employee\AttendanceRequestService;

class AttendanceRequestController extends Controller implements HasMiddleware
{
    protected AttendanceRequestService $attendanceRequestService;

    public function __construct(AttendanceRequestService $attendanceRequestService)
    {
        $this->attendanceRequestService = $attendanceRequestService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-attendance-request', only: ['index']),
            new Middleware('permission:can-approve-attendance-request', only: ['approveAttendanceRequest']),
            new Middleware('permission:can-decline-attendance-request', only: ['declineAttendanceRequest']),
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('attendanceRequests');
        $user = auth()->user();
        $attendanceRequests = $this->attendanceRequestService->getAllAttendanceRequests($user);
        $isLineManagerOrHR = $this->attendanceRequestService->isLineManagerOrHR($user);
        $requestStatuses = HelperService::getRequestStatuses();
        $responseData = [
            'attendanceRequests' => $attendanceRequests,
            'isLineManagerOrHR' => $isLineManagerOrHR,
            'requestStatuses' => $requestStatuses,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendanceRequest')
        ];
        return Inertia::render('AttendanceRequest/Index', $responseData);
    }

    public function approveAttendanceRequest(AttendanceRequest $attendanceRequest)
    {
        $isUpdated = $this->attendanceRequestService->approveAttendanceRequest($attendanceRequest);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.employeeManagement.attendanceRequest.approveAttendanceRequest.success') : __('message.custom.employeeManagement.attendanceRequest.approveAttendanceRequest.error');
        return Redirect::route('attendance-requests.index')->with($status, $message);
    }

    public function declineAttendanceRequest(AttendanceRequest $attendanceRequest)
    {
        $isUpdated = $this->attendanceRequestService->declineAttendanceRequest($attendanceRequest);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.employeeManagement.attendanceRequest.declineAttendanceRequest.success') : __('message.custom.employeeManagement.attendanceRequest.declineAttendanceRequest.error');
        return Redirect::route('attendance-requests.index')->with($status, $message);
    }
}
