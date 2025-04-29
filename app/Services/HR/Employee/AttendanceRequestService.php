<?php

namespace App\Services\HR\Employee;

use App\Constants\Constants;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HR\Employee;
use App\Models\HR\Attendance;
use App\Models\HR\AttendanceLog;
use Illuminate\Support\Facades\DB;
use App\Models\HR\AttendanceRequest;
use App\Services\ConfigurationService;
use App\Services\Core\HelperService;
use App\Services\Core\BaseModelService;
use App\Services\EmailService\EmailService;

class AttendanceRequestService extends BaseModelService
{
    protected EmailService $emailService;
    protected ConfigurationService $configurationService;

    public function __construct(EmailService $emailService, ConfigurationService $configurationService)
    {
        $this->emailService = $emailService;
        $this->configurationService = $configurationService;
    }

    public function model(): string
    {
        return AttendanceRequest::class;
    }

    public function createAttendanceRequest(User $user, Employee $employee, $validatedData)
    {
        $attendanceDate = $validatedData['attendance_date'];
        $checkInTime = $validatedData['check_in'];
        $checkOutTime = $validatedData['check_out'];
        $validatedData['check_in'] = Carbon::createFromFormat('Y-m-d H:i', $attendanceDate . ' ' . $checkInTime);
        $validatedData['check_out'] = Carbon::createFromFormat('Y-m-d H:i', $attendanceDate . ' ' . $checkOutTime);
        $additionalData = [
            'user_id' => $user->id,
            'employee_id' => $employee->id,
            'manager_id' => $employee->manager_id,
            'attendance_date' => $attendanceDate,
            'status' => Constants::PENDING
        ];
        $validatedData = array_merge($validatedData, $additionalData);

        $missingAttendanceRequest = $this->create($validatedData);

        if ($missingAttendanceRequest) {
            $this->sendAttendanceRequestEmail($user, $missingAttendanceRequest);
            $this->sendAttendancePendingEmail($missingAttendanceRequest);
        }
        
        return $missingAttendanceRequest;
    }

    public function getAllAttendanceRequests(User $user)
    {
        $attendanceRequests = '';
        if($user->hasRole(Constants::ROLE_SUPER_ADMIN) || $user->hasRole(Constants::ROLE_HR)) {
            $attendanceRequests = $this->model()::with('employee', 'manager')->orderBy('attendance_date', 'desc')->get();
        } else {
            $attendanceRequests = $this->model()::with('employee')->where('manager_id', $user->employee->id)->orderBy('attendance_date', 'desc')->get();
        }
        return $attendanceRequests;
    }

    public function isLineManagerOrHR(User $user)
    {
        if($user->hasRole(Constants::ROLE_HR)) {
            return false;
        } else if($user->hasRole(Constants::ROLE_LINE_MANAGER)) {
            return true;
        }
        return false;
    }

    public function getemployeeAttendanceRequests(Employee $employee)
    {
        $employeeAttendanceRequests = $this->model()::with('manager')->where('employee_id', $employee->id)->orderBy('attendance_date', 'desc')->get();

        return collect($employeeAttendanceRequests)->map(function ($employeeAttendanceRequest) {
            $employeeAttendanceRequest->work_location_text = HelperService::getWorkLocation($employeeAttendanceRequest->work_from);
    
            return $employeeAttendanceRequest;
        });
    }

    public function approveAttendanceRequest(AttendanceRequest $attendanceRequest)
    {
        $result = DB::transaction(function () use ($attendanceRequest) {
            $attendanceLogCommonData = [
                'employee_id' => $attendanceRequest->employee_id,
                'created_by' => $attendanceRequest->user_id,
                'updated_by' => $attendanceRequest->user_id,
            ];

            AttendanceLog::create(array_merge($attendanceLogCommonData, [
                'punch_time' => $attendanceRequest->check_in,
                'punch_type' => Constants::IN,
            ]));

            AttendanceLog::create(array_merge($attendanceLogCommonData, [
                'punch_time' => $attendanceRequest->check_out,
                'punch_type' => Constants::OUT,
            ]));

            $workedHours = HelperService::workedHoursCalculation($attendanceRequest->employee_id, $attendanceRequest->check_in, $attendanceRequest->check_out);
            $formatWoredHours = HelperService::formatTotalMinutes($workedHours);
            $effectiveHours = HelperService::effectiveHoursCalculation($attendanceRequest->employee_id, $attendanceRequest->check_in, $attendanceRequest->check_out);
            $formatEffectiveHours = HelperService::formatTotalMinutes($effectiveHours);

            $attendance = Attendance::create([
                'employee_id' => $attendanceRequest->employee_id,
                'attendance_date' => $attendanceRequest->attendance_date,
                'check_in' => $attendanceRequest->check_in,
                'check_out' =>  $attendanceRequest->check_out,
                'worked_hours' => $formatWoredHours,
                'effective_hours' => $formatEffectiveHours,
                'work_from' => $attendanceRequest->work_from
            ]);

            if ($attendance) {
                $this->sendAttendanceApproveEmail($attendanceRequest);
            }

            $attendanceRequest = $attendanceRequest->update([
                'status' => Constants::APPROVED,
                'approved_by' => auth()->user()->id
            ]);

            return $attendanceRequest;
        });

        return $result;
    }

    public function declineAttendanceRequest(AttendanceRequest $attendanceRequest)
    {
        $this->sendAttendanceDeclineEmail($attendanceRequest);

        return $attendanceRequest->update([
            'status' => Constants::DECLINED,
            'declined_by' => auth()->user()->id
        ]);
    }

    public function sendAttendanceRequestEmail(User $user, AttendanceRequest $attendanceRequest)
    {
        // Send email to all recipients
        $recipients = HelperService::getMultipleRecipients($user);
        $redirectionLink = route('attendance-requests.index');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Pending] - Attendance Request';
        foreach ($recipients as $recipient) {
            $emailData = ['user' => $recipient, 'attendanceRequest' => $attendanceRequest, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
            $emailContent = view('email-template.partials.employee-attendance-request', $emailData)->render();
            $this->emailService->send($recipient->email, $title, $emailContent);
        }
    }

    public function sendAttendancePendingEmail(AttendanceRequest $attendanceRequest)
    {
        $redirectionLink = route('employee-attendance-requests.index');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Pending] - Attendance Request';
        $emailData = ['attendanceRequest' => $attendanceRequest, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-attendance-pending', $emailData)->render();
        $this->emailService->send($attendanceRequest->employee->email, $title, $emailContent);
    }

    public function sendAttendanceApproveEmail(AttendanceRequest $attendanceRequest)
    {
        $redirectionLink = route('employee-attendance-requests.index');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Approved] - Attendance Request';
        $emailData = ['attendanceRequest' => $attendanceRequest, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-attendance-approve', $emailData)->render();
        $this->emailService->send($attendanceRequest->employee->email, $title, $emailContent);
    }

    public function sendAttendanceDeclineEmail(AttendanceRequest $attendanceRequest)
    {
        $redirectionLink = route('employee-attendance-requests.index');
        $supportDetails = $this->configurationService->getSupportDetails();
        $title = '[Rejected] - Attendance Request';
        $emailData = ['attendanceRequest' => $attendanceRequest, 'redirectionLink' => $redirectionLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-attendance-decline', $emailData)->render();
        $this->emailService->send($attendanceRequest->employee->email, $title, $emailContent);
    }
}
