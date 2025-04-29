<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeeManagement\CreateAttendanceLogRequest;
use App\Http\Requests\HR\EmployeeManagement\UpdateAttendanceLogRequest;
use App\Models\HR\AttendanceLog;
use App\Models\HR\Employee;
use App\Services\Core\HelperService;
use App\Services\HR\DepartureReasonService;
use App\Services\HR\Employee\AttendanceLogService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AttendanceLogController extends Controller implements HasMiddleware
{
    protected AttendanceLogService $attendanceLogService;
    protected DepartureReasonService $departureReasonService;

    public function __construct(AttendanceLogService $attendanceLogService, DepartureReasonService $departureReasonService)
    {
        $this->attendanceLogService = $attendanceLogService;
        $this->departureReasonService = $departureReasonService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-attendance-log', only: ['store']),
            new middleware('permission:can-edit-attendance-log', only: ['update']),
            new middleware('permission:can-delete-attendance-log', only: ['destroy']),
            new Middleware('permission:can-view-attendance-log', only: ['index']),
            new middleware('employee.departed', only: ['store', 'update', 'destroy', 'index'])
        ];
    }

    public function index(Employee $employee)
    {
        $breadcrumbs = Breadcrumbs::generate('attendanceLog', $employee);
        $attendanceLogs = $this->attendanceLogService->getAttendanceLogs($employee);
        $departureReasons = $this->departureReasonService->getDepartureReasons();
        $punchTypes = HelperService::getPunchTypes();
        $workLocations = HelperService::getWorkLocations();
        $responseData = [
            'employee' => $employee,
            'attendanceLogs' => $attendanceLogs,
            'departureReasons' => $departureReasons,
            'punchTypes' => $punchTypes,
            'workLocations' => $workLocations,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.employee.index.attendanceLog'),
        ];
        return Inertia::render('AttendanceLog/Index', $responseData);
    }

    public function store(CreateAttendanceLogRequest $request, Employee $employee)
    {
        $validatedData = $request->validated();
        $attendanceLog = $this->attendanceLogService->createAttendanceLog($validatedData);
        $status = $attendanceLog ? Constants::SUCCESS : Constants::ERROR;
        $message = $attendanceLog ? __('message.custom.employeeManagement.attendanceLog.store.success') : __('message.custom.employeeManagement.attendanceLog.store.error');
        return Redirect::route('attendance-log.index', ['employee' => $employee])->with($status, $message);
    }

    public function update(UpdateAttendanceLogRequest $request, Employee $employee, AttendanceLog $attendanceLog)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->attendanceLogService->update($attendanceLog, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? __('message.custom.employeeManagement.attendanceLog.update.success') : __('message.custom.employeeManagement.attendanceLog.update.error');
        return Redirect::route('attendance-log.index', ['employee' => $employee])->with($status, $message);
    }

    public function destroy(Employee $employee, AttendanceLog $attendanceLog)
    {
        $isDeleted = $this->attendanceLogService->delete($attendanceLog->id);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? __('message.custom.employeeManagement.attendanceLog.destroy.success') : __('message.custom.employeeManagement.attendanceLog.destroy.error');
        return Redirect::route('attendance-log.index', ['employee' => $employee])->with($status, $message);
    }
}
