<?php

namespace App\Services\Core;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Constants\Constants;
use App\Models\HR\AttendanceLog;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Passwords\PasswordBroker;

class HelperService
{
    public static function getBloodGroups()
    {
        return [
            [ 'value' => 'A+', 'text' => 'A+' ],
            [ 'value' => 'A-', 'text' => 'A-' ],
            [ 'value' => 'B+', 'text' => 'B+' ],
            [ 'value' => 'B-', 'text' => 'B-' ],
            [ 'value' => 'AB+', 'text' => 'AB+' ],
            [ 'value' => 'AB-', 'text' => 'AB-' ],
            [ 'value' => 'O+', 'text' => 'O+' ],
            [ 'value' => 'O-', 'text' => 'O-' ]
        ];
    }

    public static function getGenders()
    {
        return [
            [ 'value' => Constants::MALE, 'text' => ucfirst(Constants::MALE) ],
            [ 'value' => Constants::FEMALE, 'text' => ucfirst(Constants::FEMALE) ],
            [ 'value' => Constants::OTHERS, 'text' => ucfirst(Constants::OTHERS) ]
        ];
    }

    public static function getMaritalStatus()
    {
        return [
            [ 'value' => Constants::SINGLE, 'text' => ucfirst(Constants::SINGLE) ],
            [ 'value' => Constants::MARRIED, 'text' => ucfirst(Constants::MARRIED) ],
            [ 'value' =>  Constants::LEGAL_COHABITANT, 'text' => 'Legal cohabitant' ],
            [ 'value' =>  Constants::WIDOWER, 'text' => ucfirst(Constants::WIDOWER) ],
            [ 'value' =>  Constants::DIVORCED, 'text' => ucfirst(Constants::DIVORCED) ]
        ];
    }

    public static function getDateFormats()
    {
        return [
            [ 'value' => Constants::Y_M_D, 'text' => Constants::Y_M_D ],
            [ 'value' => Constants::M_D_Y, 'text' => Constants::M_D_Y ],
            [ 'value' => Constants::D_M_Y, 'text' => Constants::D_M_Y ]
        ];
    }

    public static function getWeekDays()
    {
        return [
            [ 'value' => Constants::SUNDAY, 'text' => ucfirst(Constants::SUNDAY) ],
            [ 'value' => Constants::MONDAY, 'text' => ucfirst(Constants::MONDAY) ],
            [ 'value' => Constants::TUESDAY, 'text' => ucfirst(Constants::TUESDAY) ],
            [ 'value' => Constants::WEDNESDAY, 'text' => ucfirst(Constants::WEDNESDAY) ],
            [ 'value' => Constants::THURSDAY, 'text' => ucfirst(Constants::THURSDAY) ],
            [ 'value' => Constants::FRIDAY, 'text' => ucfirst(Constants::FRIDAY) ],
            [ 'value' => Constants::SATURDAY, 'text' => ucfirst(Constants::SATURDAY) ]
        ];
    }

    public static function getPunchTypes()
    {
        return [
            [ 'value' => Constants::IN, 'text' => ucfirst(Constants::IN) ],
            [ 'value' => Constants::OUT, 'text' => ucfirst(Constants::OUT) ],
            [ 'value' => Constants::BREAK, 'text' => ucfirst(Constants::BREAK) ]
        ];
    }

    public static function getWorkLocation($workLocation)
    {
        $workLocations = [
            Constants::WORK_FROM_HOME => 'Work from home',
            Constants::WORK_FROM_OFFICE => 'Work from office',
            Constants::WORK_REMOTELY => 'Work remotely'
        ];
        return $workLocations[$workLocation];
    }

    public static function getWorkLocations() {
        return [
            [ 'value' => Constants::WORK_FROM_HOME, 'text' => 'Work from home' ],
            [ 'value' => Constants::WORK_FROM_OFFICE, 'text' => 'Work from office' ],
            [ 'value' => Constants::WORK_REMOTELY, 'text' => 'Work remotely' ]
        ];
    }

    public static function getRequestStatuses() {
        return [
            [ 'value' => Constants::PENDING, 'text' => ucfirst(Constants::PENDING) ],
            [ 'value' => Constants::APPROVED, 'text' => ucfirst(Constants::APPROVED) ],
            [ 'value' => Constants::DECLINED, 'text' => ucfirst(Constants::DECLINED) ]
        ];
    }

    public static function workedHoursCalculation($employeeId, $checkInTime, $checkOutTime)
    {
        $checkIn = Carbon::parse($checkInTime);
        $checkOut = Carbon::parse($checkOutTime);
        $totalMinutes = $checkIn->diffInMinutes($checkOut);
        
        // Retrieve all "out" punch logs for the employee within the given check-in and check-out time range to subtract break time from the total time.
        $checkOutLogs = AttendanceLog::where('employee_id', $employeeId)
            ->where('punch_type', Constants::OUT)
            ->whereBetween('punch_time', [$checkInTime, $checkOutTime])
            ->orderBy('punch_time')
            ->get();

        // Loop through each "out" punch log to calculate the break time.
        foreach ($checkOutLogs as $checkOutLog) {
            $nextCheckInLog = AttendanceLog::where('employee_id', $employeeId)
                ->where('punch_type', Constants::IN)
                ->whereBetween('punch_time', [$checkInTime, $checkOutTime])
                ->where('punch_time', '>', $checkOutLog->punch_time)
                ->orderBy('punch_time')
                ->first();

            // If there is a corresponding "in" punch, subtract the break time from the total minutes worked.
            if ($nextCheckInLog) {
                $breakMinutes = Carbon::parse($checkOutLog->punch_time)->diffInMinutes(Carbon::parse($nextCheckInLog->punch_time));
                $totalMinutes -= $breakMinutes;
            }
        }
        return $totalMinutes;
    }

    public static function formatTotalMinutes($totalMinutes)
    {
        $hours = intdiv($totalMinutes, 60);
        $minutes = $totalMinutes % 60;

        return "{$hours} hours and {$minutes} minutes";
    }

    public static function effectiveHoursCalculation($employeeId, $checkInTime, $checkOutTime)
    {
        $totalMinutes = self::workedHoursCalculation($employeeId, $checkInTime, $checkOutTime);

        // Retrieve all "break" punch logs for the employee within the given check-in and check-out time range to subtract break time from the total time. 
        AttendanceLog::where('employee_id', $employeeId)
            ->where('punch_type', Constants::BREAK)
            ->whereBetween('punch_time', [$checkInTime, $checkOutTime])
            ->orderBy('punch_time')
            ->each(function ($breakLog) use (&$totalMinutes, $employeeId) {
                // For each "break" punch, find the next "in" punch after it.
                $nextCheckInLog = AttendanceLog::where('employee_id', $employeeId)
                    ->where('punch_type', Constants::IN)
                    ->where('punch_time', '>', $breakLog->punch_time)
                    ->orderBy('punch_time')
                    ->first();

                // If there is a corresponding "in" punch, subtract the break time from the total minutes worked.
                if ($nextCheckInLog) {
                    $breakMinutes = Carbon::parse($breakLog->punch_time)->diffInMinutes(Carbon::parse($nextCheckInLog->punch_time));
                    $totalMinutes -= $breakMinutes;
                }
            });
        
        return $totalMinutes;
    }

    public static function getResetPasswordLink(User $user)
    {
        $token = app(PasswordBroker::class)->createToken($user);
        return url('reset-password/' . $token . '?email=' . $user->email);
    }

    public static function getEmailVerificationLink(User $user)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
    }

    public static function getMultipleRecipients(User $user)
    {
        $recipients = collect();
        $employee = $user->employee;

        // Get the Line Manager
        $manager = $employee->manager;
        if ($manager && $manager->user && $manager->user->hasRole(Constants::ROLE_LINE_MANAGER)) {
            $recipients->push($manager->user);
        }
        
        // Get HR users who belong to the same branch as the current user
        $branch = $employee->branch;
        if ($branch) {
            $hrUsers = User::whereHas('employee', function ($query) use ($branch) {
                    $query->where('branch_id', $branch->id);
                })
                ->role(Constants::ROLE_HR)
                ->get();
            $recipients = $recipients->merge($hrUsers);
        }

        // Get all Super Admin users
        $superAdminUsers = User::role(Constants::ROLE_SUPER_ADMIN)->get();
        $recipients = $recipients->merge($superAdminUsers);

        return $recipients;
    }

    public static function getAllocationTypes()
    {
        return [
            [ 'value' => Constants::REGULAR, 'text' => ucfirst(Constants::REGULAR) ]
        ];
    }

    public static function getAllocationModes()
    {
        return [
            [ 'value' => Constants::COMPANY, 'text' => ucfirst(Constants::COMPANY) ],
            [ 'value' => Constants::EMPLOYEE, 'text' => ucfirst(Constants::EMPLOYEE) ],
            [ 'value' => Constants::DEPARTMENT, 'text' => ucfirst(Constants::DEPARTMENT) ]
        ];
    }

    public static function getLeaveValidationType($leaveValidationType)
    {
        $leaveValidationTypes = [
            Constants::HR => ucfirst(Constants::HR),
            Constants::MANAGER => ucfirst(Constants::MANAGER),
            Constants::BOTH => 'Both Hr and Manager',
            Constants::NO_VALIDATIONS => 'No validations'
        ];
        return $leaveValidationTypes[$leaveValidationType];
    }

    public static function getLeaveValidationTypes() {
        return [
            [ 'value' => Constants::HR, 'text' => ucfirst(Constants::HR) ],
            [ 'value' => Constants::MANAGER, 'text' => ucfirst(Constants::MANAGER) ],
            [ 'value' => Constants::BOTH, 'text' => 'Both Hr and Manager' ],
            [ 'value' => Constants::NO_VALIDATIONS, 'text' => 'No validations']
        ];
    }

    public static function getLeaveTypeColors() {
        return [
            [ 'value' => '#4caf50', 'text' => 'Light Green' ],
            [ 'value' => '#ff6b6b', 'text' => 'Light Red' ],
            [ 'value' => '#42a5f5', 'text' => 'Sky Blue' ],
            [ 'value' => '#ffb6c1', 'text' => 'Soft Pink' ],
            [ 'value' => '#9575cd', 'text' => 'Purple' ],
            [ 'value' => '#ff9800', 'text' => 'Orange' ],
            [ 'value' => '#ffeb3b', 'text' => 'Yellow' ],
            [ 'value' => '#303f9f', 'text' => 'Dark Blue' ]
        ];
    }

    public static function getRequestUnits() {
        return [
            [ 'value' => Constants::DAY, 'text' => ucfirst(Constants::DAY) ],
            [ 'value' => Constants::HOUR, 'text' => ucfirst(Constants::HOUR) ]
        ];
    }

    public static function isLineManagerOrHR(User $user)
    {
        if($user->hasRole(Constants::ROLE_HR)) {
            return false;
        } else if($user->hasRole(Constants::ROLE_LINE_MANAGER)) {
            return true;
        }
        return false;
    }

    public static function isSuperAdminOrHR(User $user)
    {
        if($user->hasAnyRole([Constants::ROLE_SUPER_ADMIN, Constants::ROLE_HR])) {
            return true;
        }

        return false;
    }

    public static function isEmployee(User $user)
    {
        if($user->hasRole(Constants::ROLE_EMPLOYEE)) {
            return true;
        }
        
        return false;
    }

    public static function captureException(Exception $e)
    {
        \Sentry\captureException($e);
    }
}