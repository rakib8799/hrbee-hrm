<?php

use App\Http\Controllers\HR\Leave\LeaveController;
use Inertia\Inertia;
use App\Http\Middleware\Language;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HR\DepartmentController;
use App\Http\Controllers\HR\JobPositionController;
use App\Http\Controllers\Permission\RoleController;
use App\Http\Controllers\Language\LanguageController;
use App\Http\Controllers\HR\Leave\LeaveTypeController;
use App\Http\Controllers\Language\LocalizationController;
use App\Http\Controllers\HR\Leave\CalendarLeaveController;
use App\Http\Controllers\HR\Leave\PublicHolidayController;
use App\Http\Controllers\HR\Leave\LeaveAllocationController;
use App\Http\Controllers\HR\Subscription\SubscriptionController;
use App\Http\Controllers\HR\Employee\EmployeeAttendanceController;
use App\Http\Controllers\HR\EmployeeManagement\EmployeeController;
use App\Http\Controllers\HR\EmployeeManagement\AttendanceController;
use App\Http\Controllers\HR\Employee\EmployeeAttendanceLogController;
use App\Http\Controllers\HR\EmployeeManagement\AttendanceLogController;
use App\Http\Controllers\HR\Employee\EmployeeAttendanceRequestController;
use App\Http\Controllers\HR\Employee\EmployeeChartController;
use App\Http\Controllers\HR\EmployeeManagement\EmployeeDocumentController;
use App\Http\Controllers\HR\EmployeeManagement\AttendanceRequestController;
use App\Http\Controllers\TimezoneController;

Route::get('/localization/{locale}', [LocalizationController::class, 'localization'])->name('localization');
Route::get('/language-options', [LanguageController::class, 'getLanguageOptions'])->name('getLanguageOptions');

Route::middleware(Language::class)
    ->group(function () {
        Route::get('/', function () {
            return Inertia::render('Auth/Login', [
                'canLogin' => Route::has('login'),
                'canRegister' => Route::has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
                'pageTitle' => __('pageTitle.custom.login'),
            ]);
        })->middleware('guest');

        Route::get('/signup-confirmation', function () {
            return Inertia::render('Auth/SignUpConfirmation', [
                'pageTitle' => __('pageTitle.custom.auth.signUpConfirmation')
            ]);
        });

        Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

        Route::middleware('auth')->group(function () {
            // Alert route
            Route::get('/alert', function () {
                return Inertia::render('Error/Alert');
            })->name('alert');

            // Profile related routes
            Route::prefix('profile')->group(function() {
                Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
                Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
            });

            // Configuration related routes
            Route::prefix('configurations')->group(function() {
                Route::get('/', [ConfigurationController::class, 'details'])->name('configurations.details');
                Route::get('basic-info', [ConfigurationController::class, 'basicInfo'])->name('configurations.basicInfo');
                Route::get('additional-info', [ConfigurationController::class, 'additionalInfo'])->name('configurations.additionalInfo');
                Route::get('contact-info', [ConfigurationController::class, 'contactInfo'])->name('configurations.contactInfo');

                Route::patch('basic-info', [ConfigurationController::class, 'updateBasicInfo'])->name('configurations.updateBasicInfo');
                Route::patch('additional-info', [ConfigurationController::class, 'updateAdditionalInfo'])->name('configurations.updateAdditionalInfo');
                Route::patch('contact-info', [ConfigurationController::class, 'updateContactInfo'])->name('configurations.updateContactInfo');
            });

            // Timezone and date-format related route
            Route::get('/timezone/date-format', [TimezoneController::class, 'getTimezoneAndDateFormat'])->name('timezone.dateFormat');

            // Roles related routes
            Route::resource('roles', RoleController::class);
            Route::post('assign-permission', [RoleController::class, 'assignPermissionToRole']);
            Route::delete('remove-assigned-permission', [RoleController::class, 'removePermissionFromRole']);
            Route::prefix('roles/{role}')->group(function() {
                Route::patch('change-status', [RoleController::class, 'changeStatus'])->name('roles.changeStatus');
                Route::delete('remove-user/{user}', [RoleController::class, 'removeUserFromRole'])->name('roles.removeUserFromRole');
            });

            // User related routes
            Route::resource('/users', UserController::class);
            Route::prefix('users/{user}')->group(function() {
                Route::patch('update-details', [UserController::class, 'updateDetails'])->name('users.updateDetails');
                Route::patch('update-email', [UserController::class, 'updateEmail'])->name('users.updateEmail');
                Route::patch('update-roles', [UserController::class, 'updateRoles'])->name('users.updateRoles');
                Route::patch('update-password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
                Route::patch('change-status', [UserController::class, 'changeStatus'])->name('users.changeStatus');
            });
            
            // Branch related routes
            Route::get('/branches/get-branches', [BranchController::class, 'getBranches'])->name('branches.getBranches');
            Route::post('/branches/store-branch', [BranchController::class, 'storeBranch'])->name('branches.storeBranch');

            Route::resource('branches', BranchController::class);
            Route::prefix('branches/{branch}')->group(function() {
                Route::get('/employee-options', [BranchController::class, 'getEmployeeOptionsByBranch']);
                Route::get('/employee/create', [EmployeeController::class, 'create'])->name('branches.createBranchEmployee');
                Route::post('/employee/store', [EmployeeController::class, 'store'])->name('branches.storeBranchEmployee');
                Route::patch('/change-status', [BranchController::class, 'changeStatus'])->name('branches.changeStatus');
            });
            
            // Job-position related routes
            Route::get('job-positions/get-job-positions', [JobPositionController::class, 'getJobPositions'])->name('job-positions.getJobPositions');
            Route::post('job-positions/storeJobPosition', [JobPositionController::class, 'storeJobPosition'])->name('job-positions.storeJobPosition');

            Route::resource('job-positions', JobPositionController::class);
            Route::patch('/job-positions/{job_position}/change-status', [JobPositionController::class, 'changeStatus'])->name('job-positions.changeStatus');

            // Employee related routes
            Route::resource('employees', EmployeeController::class);
            Route::prefix('employees/{employee}')->group(function() {
                Route::get('basic-info', [EmployeeController::class, 'basicInfo'])->name('employees.basicInfo');
                Route::patch('basic-info', [EmployeeController::class, 'updateBasicInfo'])->name('employees.updateBasicInfo');
                Route::get('additional-info', [EmployeeController::class, 'additionalInfo'])->name('employees.additionalInfo');
                Route::patch('additional-info', [EmployeeController::class, 'updateAdditionalInfo'])->name('employees.updateAdditionalInfo');
                Route::get('work-info', [EmployeeController::class, 'workInfo'])->name('employees.workInfo');
                Route::patch('work-info', [EmployeeController::class, 'updateWorkInfo'])->name('employees.updateWorkInfo');
                Route::get('departure-info', [EmployeeController::class, 'departureInfo'])->name('employees.departureInfo');
                Route::patch('process-employee-departure', [EmployeeController::class, 'processEmployeeDeparture'])->name('employees.processEmployeeDeparture');
                Route::patch('process-employee-rejoin', [EmployeeController::class, 'processEmployeeRejoin'])->name('employees.processEmployeeRejoin');
                Route::patch('change-status', [EmployeeController::class, 'changeStatus'])->name('employees.changeStatus');
                Route::resource('documents', EmployeeDocumentController::class);
                Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
                Route::resource('attendance-log', AttendanceLogController::class);
                Route::post('missing-punch', [AttendanceLogController::class, 'addMissingPunch'])->name('attendance-log.addmissingPunch');
            });

            Route::prefix('attendance-requests')->group(function () {
                Route::get('/', [AttendanceRequestController::class, 'index'])->name('attendance-requests.index');
                Route::patch('/{attendanceRequest}/approve', [AttendanceRequestController::class, 'approveAttendanceRequest'])->name('attendance-requests.approve');
                Route::patch('/{attendanceRequest}/decline', [AttendanceRequestController::class, 'declineAttendanceRequest'])->name('attendance-requests.decline');
            });

            // Employees section related routes
            Route::middleware(['role.isEmployee'])->group(function() {
                Route::resource('employee-attendance-logs', EmployeeAttendanceLogController::class);
                Route::post('/employee-attendance-logs/checkout', [EmployeeAttendanceLogController::class, 'checkout'])->name('employee-attendance-logs.checkout');
                Route::get('/employee-attendances', [EmployeeAttendanceController::class, 'index'])->name('employee-attendances.index');
                Route::resource('employee-attendance-requests', EmployeeAttendanceRequestController::class);

                // Employee leave related route
                Route::get('/employee-leave-requests', [LeaveController::class, 'employeeLeaveRequests'])->name('employee.leaveRequests');
                
                // Employee chart related routes
                Route::get('/employee-chart', [EmployeeChartController::class, 'getChartData'])->name('employee.chart');
            });

            // Leave request related routes
            Route::prefix('leaves')->group(function() {
                Route::get('/', [LeaveController::class, 'leaveRequests'])->name('leaves.leaveRequests');
                Route::prefix('{leave}')->group(function() {
                    Route::patch('/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
                    Route::patch('/decline', [LeaveController::class, 'decline'])->name('leaves.decline');
                });
            });

            // Employees leave section related routes
            Route::prefix('employee-leaves')->group(function() {
                Route::get('/', [LeaveController::class, 'employeeLeaves'])->name('leaves.employeeLeaves');
                Route::post('/', [LeaveController::class, 'storeEmployeeLeave'])->name('leaves.storeEmployeeLeave');
            });

            // Department related routes
            Route::resource('departments', DepartmentController::class);
            Route::patch('/departments/{department}/change-status', [DepartmentController::class, 'changeStatus'])->name('departments.changeStatus');

            // Leave type related routes
            Route::resource('leave-types', LeaveTypeController::class);
            Route::prefix('leave-types/{leave_type}')->group(function() {
                Route::patch('/change-status', [LeaveTypeController::class, 'changeStatus'])->name('leave-types.changeStatus');
                Route::get('/allocation-info', [LeaveTypeController::class, 'leaveTypeAllocation'])->name('leave-types.leaveTypeAllocation');
                Route::get('/allocation', [LeaveAllocationController::class, 'create'])->name('leave-types.createLeaveTypeAllocation');
                Route::post('/allocation', [LeaveAllocationController::class, 'store'])->name('leave-types.storeLeaveTypeAllocation');
            });

            // Leave allocation related routes
            Route::resource('leave-allocations', LeaveAllocationController::class);
            Route::prefix('leave-allocations/{leave_allocation}')->group(function() {
                Route::patch('/approve', [LeaveAllocationController::class,'approve'])->name('leave-allocations.approve');
                Route::patch('/decline', [LeaveAllocationController::class,'decline'])->name('leave-allocations.decline');
            });

            // Public holiday related routes
            Route::resource('public-holidays', PublicHolidayController::class)->parameters([
                'public-holidays' => 'calendar_leave'
            ]);

            // Calendar leave related routes
            Route::get('/calendar-leaves', [CalendarLeaveController::class, 'index'])->name('calendar-leaves.index');

            // Subscription related routes
            Route::resource('/subscriptions', SubscriptionController::class);
        });
    }
);

require __DIR__.'/auth.php';

