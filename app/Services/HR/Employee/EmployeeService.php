<?php

namespace App\Services\HR\Employee;

use App\Constants\Constants;
use Carbon\Carbon;
use App\Models\User;
use App\Models\HR\Employee;
use App\Models\Branch\Branch;
use App\Services\ConfigurationService;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Services\Core\HelperService;
use Illuminate\Support\Facades\Hash;
use App\Services\Core\BaseModelService;
use App\Services\EmailService\EmailService;

class EmployeeService extends BaseModelService
{
    protected UserService $userService;
    private EmailService $emailService;
    private ConfigurationService $configurationService;

    public function __construct(UserService $userService, EmailService $emailService, ConfigurationService $configurationService)
    {
        $this->userService = $userService;
        $this->emailService = $emailService;
        $this->configurationService = $configurationService;
    }

    public function model(): string
    {
        return Employee::class;
    }

    public function getEmployees()
    {
        $employees = $this->model()::with('country', 'user')->get()->map(function ($employee) {
            if($employee->user->last_login_at) {
                $lastLogin = Carbon::parse($employee->user->last_login_at);
                $employee->user->last_login_at = $lastLogin->diffForHumans();
            } else {
                $employee->user->last_login_at = "Never logged in";
            }
            return $employee;
        });
        return $employees;
    }

    public function getActiveEmployees()
    {
        $activeEmployees = $this->model()::where('is_active', 1)->get();
        return $activeEmployees;
    }

    public function createEmployee($validatedData, Branch $branch = null)
    {
        $result = DB::transaction(function () use ($validatedData, $branch) {
            $email = $validatedData['email'];
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $validatedData['first_name'] . ' ' . $validatedData['last_name'],
                    'password' => Hash::make('12345'),
                ]
            );

            $user->assignRole(Constants::ROLE_EMPLOYEE);
            $validatedData['user_id'] = $user->id;
            if ($branch) {
                $validatedData['branch_id'] = $branch->id;
                $validatedData['manager_id'] = $branch->manager_id ?? null;
                $validatedData['leave_manager_id'] = $branch->manager_id ?? null;
            }
            $employee = $this->create($validatedData);
            return $employee;
        });
        return $result;
    }

    public function sendEmployeeEmailVerification(Employee $employee)
    {
        $user = $employee->user;
        $supportDetails = $this->configurationService->getSupportDetails();
        $resetPasswordLink = HelperService::getResetPasswordLink($user);
        $title = 'Employee Email Verification';
        $emailData = ['employee' => $employee, 'resetPasswordLink' => $resetPasswordLink, 'supportDetails' => $supportDetails, 'pageTitle' => $title];
        $emailContent = view('email-template.partials.employee-email-verification', $emailData)->render();
        $this->emailService->send($employee->email, $title, $emailContent);
    }

    public function updateEmployee(Employee $employee, $validatedData)
    {
        $result = DB::transaction(function () use($employee, $validatedData) {
            if(isset($validatedData['manager_id'])) {
                $manager = $this->find($validatedData['manager_id']);
                $manager->user->assignRole(Constants::ROLE_LINE_MANAGER);
            }
            $this->update($employee, $validatedData);

            if (array_key_exists('job_positions', $validatedData)) {
                $employee->jobPositions()->sync($validatedData['job_positions']);
            }

            // Update user name
            if(isset($validatedData['first_name'])) {
                $user = $employee->user;
                if($user) {
                    $user->name = $validatedData['first_name'].' '.$validatedData['last_name'];
                    $user->save();
                }
            }
            return $employee;
        });
        return $result;
    }

    public function deleteEmployee(Employee $employee)
    {
        $this->delete($employee->id);
        return true;
    }

    /**
     * Get user, country, department, branch, manager and leave manager of an employee
     */
    public function getEmployeeDetails(Employee $employee)
    {
        return $employee->load('user', 'country', 'department', 'branch', 'manager', 'leaveManager');
    }

    public function changeStatus(Employee $employee, $isActive)
    {
        $result = DB::transaction(function () use ($employee, $isActive) {
            $this->update($employee, ['is_active' => !$isActive]);
            if($employee && $employee->is_active == !$isActive){
                $this->userService->find($employee->user_id)->update(['is_active' => !$isActive]);
            }
            return $employee;
        });
        return $result;
    }

    public function handleDeparture(Employee $employee, $validatedData)
    {
        $employee->is_active = false;
        $employee->is_departed = true;
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $updatedEmployee = $this->updateEmployee($employee, $validatedData);
            if($updatedEmployee && $employee->is_departed == true){
                $this->userService->find($employee->user_id)->update(['is_active' => false]);
            }
            return true;
        });
        return $result ? true : false;
    }

    public function handleRejoin(Employee $employee, $validatedData)
    {
        $employee->is_active = true;
        $employee->is_departed = false;
        $result = DB::transaction(function () use ($employee, $validatedData) {
            $updatedEmployee = $this->updateEmployee($employee, $validatedData);
            if($updatedEmployee && $employee->is_departed == false){
                $this->userService->find($employee->user_id)->update(['is_active' => true]);
            }
            return true;
        });
        return $result ? true : false;
    }

    public function getEmployeeDetailsForAttendance($staffId)
    {
        $employee = $this->model()::with(['attendances' => function ($query) {
            $query->latest('punch_time')
                ->limit(1);
        }])
            ->where('staff_id', $staffId)
            ->first();
        return $this->transformEmployeeDetails($employee);
    }

    protected function transformEmployeeDetails($employee)
    {
        if($employee) {
            $lastPunch = optional($employee->attendances->first());
            return [
                'employee_id' => $employee->id,
                'name' => $this->getEmployeeFullName($employee),
                'last_punch' => $lastPunch->toArray() ?? null
            ];
        }
    }

    public function getEmployeeFullName(Employee $employee)
    {
        return $employee->first_name." ".$employee->last_name;
    }

    public function getEmployeesByBranch(Branch $branch)
    {
        $employees = $branch->employees()->where('is_active', 1)->get();
        $result = $employees->map(function ($employee) {
            return [
                'value' => $employee->id,
                'text' => $this->getEmployeeFullName($employee)
            ];
        });
        return $result->all();
    }

    /**
     * Get leave types and associated leave allocations that are assigned to logged in employee
     * If same leave type has more than one allocations,
     * It will be grouped using leave_type_id, date_from and date_to to count total_number of days
     */
    public function getEmployeeLeaveAllocations(Employee $employee)
    {
        $currentYear = Carbon::now()->year;
        $leaveAllocations = $employee->leaveAllocation()->with('leaveType')
        ->select('leave_type_id', 'date_from', 'date_to', DB::raw('SUM(number_of_days) as number_of_days'))
        ->where('status', Constants::APPROVED)
        ->where(function($query) use ($currentYear) {
            $query->whereYear('date_from', $currentYear)
                    ->whereYear('date_to', $currentYear);
        })
        ->groupBy('leave_type_id', 'date_from', 'date_to')
        ->get()
        ->sortBy(function($allocation) {
            return $allocation->leaveType->sequence;
        })->values();

        return $leaveAllocations;
    }

    public function getEmployeeLeaveRemainingDays(Employee $employee, $employeeLeaveAllocations)
    {
        $totalRemainingDays = [];

        foreach ($employeeLeaveAllocations as $leaveAllocation) {
            $totalAllocatedDays = $leaveAllocation['number_of_days'];
            $leaveTypeId = $leaveAllocation['leave_type_id'];
            $dateFrom = $leaveAllocation['date_from'];
            $dateTo = $leaveAllocation['date_to'];

            $totalDaysUsed = $employee->leaves()
            ->where('leave_type_id', $leaveTypeId)
            ->whereBetween('date_from', [$dateFrom, $dateTo])
            ->WhereBetween('date_to', [$dateFrom, $dateTo])
            ->where('status', Constants::APPROVED)
            ->sum('number_of_days');

            $totalRemainingDays[] = $totalAllocatedDays - (int) $totalDaysUsed;
        }

        return $totalRemainingDays;
    }
}
