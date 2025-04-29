<?php

namespace App\Services\HR\Leave;

use Carbon\Carbon;
use App\Constants\Constants;
use App\Models\HR\Employee;
use App\Models\HR\Leave\Leave;
use App\Models\HR\Leave\LeaveType;
use App\Models\User;
use App\Services\ConfigurationService;
use App\Services\Core\BaseModelService;
use App\Services\Core\HelperService;
use App\Services\EmailService\EmailService;
use Illuminate\Support\Facades\DB;

class LeaveService extends BaseModelService
{
    protected CalendarLeaveService $calendarLeaveService;
    protected EmailService $emailService;
    protected ConfigurationService $configurationService;

    public function __construct(CalendarLeaveService $calendarLeaveService, EmailService $emailService, ConfigurationService $configurationService)
    {
        $this->calendarLeaveService = $calendarLeaveService;
        $this->emailService = $emailService;
        $this->configurationService = $configurationService;
    }

    public function model(): string
    {
        return Leave::class;
    }

    public function getLeaves(User $user)
    {
        $leaveRequests = '';
        if($user->hasRole(Constants::ROLE_SUPER_ADMIN) || $user->hasRole(Constants::ROLE_HR)) {
            $leaveRequests = $this->model()::with(['employee', 'leaveType'])->get();
        } else {
            $leaveRequests = $this->model()::with(['employee', 'leaveType'])
            ->where('manager_id', $user->employee->id)
            ->whereHas('leaveType', function ($query) {
                $query->where('leave_validation_type', Constants::MANAGER)->orWhere('leave_validation_type', Constants::BOTH);
            })
            ->get();
        }
        return $leaveRequests;
    }

    public function getEmployeeLeaveRequests(Employee $employee)
    {
        $employeeLeaveRequests = $this->model()::with('leaveType')->where('employee_id', $employee->id)->orderBy('id', 'desc')->get();
        return $employeeLeaveRequests;
    }

    public function createLeave($validatedData)
    {
        $requestDateFrom = Carbon::parse($validatedData['request_date_from']);
        $requestDateTo = Carbon::parse($validatedData['request_date_to']);
        $totalDaysInRange = (int) $requestDateFrom->diffInDays($requestDateTo) + 1;

        $validatedData['employee_id'] = auth()->user()->employee->id;
        $leaveType = LeaveType::find($validatedData['leave_type_id']);
        if ($leaveType->leave_validation_type === Constants::MANAGER || $leaveType->leave_validation_type === Constants::BOTH) {
            $manager = auth()->user()->employee->manager;
            if ($manager) {
                $validatedData['manager_id'] = $manager->id;
                $validatedData['user_id'] = $manager->user_id;
            }
        } else if ($leaveType->leave_validation_type === Constants::NO_VALIDATIONS) {
            $validatedData['status'] = Constants::APPROVED;
        }
        $validatedData['holiday_type'] = Constants::EMPLOYEE;
        $validatedData['date_from'] = $validatedData['request_date_from'];
        $validatedData['date_to'] = $validatedData['request_date_to'];
        $validatedData['number_of_days'] = $totalDaysInRange;
        $validatedData['number_of_hours'] = $totalDaysInRange * 8;

        $result = DB::transaction(function () use ($validatedData) {
            $leave = $this->create($validatedData);
            if ($leave) {
                $user = auth()->user();
                $this->sendLeaveRequestEmail($user, $leave);
                $this->sendLeavePendingEmail($leave);
            }

            if ($leave && isset($validatedData['status']) && $validatedData['status'] === Constants::APPROVED) {
                $this->calendarLeaveService->createCalendarLeave($leave, $validatedData);
            } 

            return $leave;
        });
        return $result;
    }

    public function approveLeave(Leave $leave)
    {
        $leaveStatus = $leave->status;
        $leaveValidationType = $leave->leaveType->leave_validation_type;
        $user = auth()->user();
        $employee = $user->employee;

        $validatedData['status'] = Constants::APPROVED;
        $validatedData['first_approver_id'] = isset($employee) ? $employee->id : null;
        $validatedData['user_id'] = !isset($employee) ? $user->id : null;

        if ($leaveValidationType === Constants::BOTH) {
            if ($leaveStatus === Constants::PENDING) {
                $validatedData['status'] = Constants::APPROVED_FIRST;
            } elseif ($leaveStatus === Constants::APPROVED_FIRST) {
                $validatedData['second_approver_id'] = isset($employee) ? $employee->id : null;
            }
        }

        $result = DB::transaction(function () use ($leave, $validatedData) {
            $isUpdatedLeave = $this->update($leave, $validatedData);
            if($isUpdatedLeave && $leave->status === Constants::APPROVED) {
                $this->calendarLeaveService->createCalendarLeave($leave, $validatedData);
                $this->sendLeaveApproveEmail($leave);
            }
            return $isUpdatedLeave;
        });
        return $result;
    }

    public function declineLeave(Leave $leave)
    {
        $this->sendLeaveDeclineEmail($leave);
        $data['status'] = Constants::DECLINED;
        $data['is_active'] = false;
        return $this->update($leave, $data);
    }

    public function sendLeaveRequestEmail(User $user, Leave $leave)
    {
        // Send email to all recipients
        $recipients = HelperService::getMultipleRecipients($user);
        $redirectionLink = route('leaves.leaveRequests');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Pending] - Leave Request';
        foreach ($recipients as $recipient) {
            $emailData = ['user' => $recipient, 'leaveRequest' => $leave, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
            $emailContent = view('email-template.partials.employee-leave-request', $emailData)->render();
            $this->emailService->send($recipient->email, $title, $emailContent);
        }
    }

    public function sendLeavePendingEmail(Leave $leave)
    {
        $redirectionLink = route('employee.leaveRequests');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Pending] - Leave Request';
        $emailData = ['leaveRequest' => $leave, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-leave-pending', $emailData)->render();
        $this->emailService->send($leave->employee->email, $title, $emailContent);
    }

    public function sendLeaveApproveEmail(Leave $leave)
    {
        $redirectionLink = route('employee.leaveRequests');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Approved] - Leave Request';
        $emailData = ['leaveRequest' => $leave, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-leave-approve', $emailData)->render();
        $this->emailService->send($leave->employee->email, $title, $emailContent);
    }

    public function sendLeaveDeclineEmail(Leave $leave)
    {
        $redirectionLink = route('employee.leaveRequests');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Rejected] - Leave Request';
        $emailData = ['leaveRequest' => $leave, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-leave-decline', $emailData)->render();
        $this->emailService->send($leave->employee->email, $title, $emailContent);
    }
}
