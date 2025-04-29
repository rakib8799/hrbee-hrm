<?php

namespace App\Http\Controllers;

use App\Models\Branch\Branch;
use App\Models\HR\JobPosition;
use App\Services\Core\HelperService;
use App\Services\HR\Employee\AttendanceService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Inertia\Inertia;

class DashboardController extends Controller
{   
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('dashboard');
        $permissions = session('permissions');

        $user = auth()->user();
        $isSuperAdminOrHR = HelperService::isSuperAdminOrHR($user);
        $isEmployee = HelperService::isEmployee($user);
        $totalBranch = Branch::count();
        $totalJobPosition = JobPosition::count();

        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.home'),
            'permissions' => $permissions,
            'isSuperAdminOrHR' => $isSuperAdminOrHR,
            'isEmployee' => $isEmployee,
            'totalBranch' => $totalBranch,
            'totalJobPosition' => $totalJobPosition
        ];
        
        if($isSuperAdminOrHR) {
            $employeeAttendances = $this->attendanceService->getEmployeeTodayAttendance($user);
            $responseData = array_merge($responseData, $employeeAttendances);
        }

        return Inertia::render('Dashboard', $responseData);
    }
}
