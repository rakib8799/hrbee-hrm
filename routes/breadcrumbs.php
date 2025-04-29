<?php // routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.

use App\Models\User;
use App\Models\HR\Employee;
use App\Models\Branch\Branch;
use App\Models\HR\Department;
use App\Models\HR\Leave\CalendarLeave;
use Spatie\Permission\Models\Role;
use Diglactic\Breadcrumbs\Breadcrumbs;
use App\Models\HR\Leave\LeaveAllocation;
use App\Models\HR\Leave\LeaveType;
use App\Models\Subscription\Subscription;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.

// Dashboard as Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('pageTitle.custom.home'), route('dashboard'));
});

// Settings
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.configuration.parentInfo'), route('configurations.details'));
});

// Settings - Basic Info
Breadcrumbs::for('settingsBasicInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.basicInfo'), route('configurations.basicInfo'));
});

// Settings - Additional Info
Breadcrumbs::for('settingsAdditionalInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.additionalInfo'), route('configurations.additionalInfo'));
});

// Settings - Contact Info
Breadcrumbs::for('settingsContactInfo', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('breadcrumb.custom.configuration.childInfo.contactInfo'), route('configurations.contactInfo'));
});

// User Management Menu
Breadcrumbs::for('userManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.user.parentInfo'));
});

// Roles
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('userManagement');
    $trail->push(__('breadcrumb.custom.role.childInfo.index'), route('roles.index'));
});

// Add Roles
Breadcrumbs::for('addRole', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.create'), route('roles.create'));
});

// Edit Roles
Breadcrumbs::for('editRole', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.edit'), route('roles.edit', $role));
});

// Roles Details
Breadcrumbs::for('roleDetails', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('roles');
    $trail->push(__('breadcrumb.custom.role.childInfo.details'), route('roles.show', $role));
});

// User List
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('userManagement');
    $trail->push(__('breadcrumb.custom.user.childInfo.index'), route('users.index'));
});

// Add User
Breadcrumbs::for('addUser', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.create'), route('users.create'));
});

// Edit User
Breadcrumbs::for('editUser', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.edit'), route('users.edit', $user));
});

// User Details
Breadcrumbs::for('userDetails', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('users');
    $trail->push(__('breadcrumb.custom.user.childInfo.details'), route('users.show', $user));
});

// Branch Menu
Breadcrumbs::for('branch', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.branch.parentInfo'));
});

// Branch List
Breadcrumbs::for('branches', function (BreadcrumbTrail $trail) {
    $trail->parent('branch');
    $trail->push(__('breadcrumb.custom.branch.childInfo.index'), route('branches.index'));
});

// Add Branch
Breadcrumbs::for('addBranch', function (BreadcrumbTrail $trail) {
    $trail->parent('branches');
    $trail->push(__('breadcrumb.custom.branch.childInfo.create'), route('branches.create'));
});

// Edit Branch
Breadcrumbs::for('editBranch', function (BreadcrumbTrail $trail, Branch $branch) {
    $trail->parent('branches');
    $trail->push(__('breadcrumb.custom.branch.childInfo.edit'), route('branches.edit', $branch));
});

// Branch Details
Breadcrumbs::for('branchDetails', function (BreadcrumbTrail $trail, Branch $branch) {
    $trail->parent('branches');
    $trail->push(__('breadcrumb.custom.branch.childInfo.details'), route('employees.show', $branch));
});

// Employee Menu
Breadcrumbs::for('employee', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.employee.parentInfo'));
});

// Job Position List
Breadcrumbs::for('jobPositions', function (BreadcrumbTrail $trail) {
    $trail->parent('employee');
    $trail->push(__('breadcrumb.custom.jobPosition.childInfo'), route('job-positions.index'));
});

// Employee List
Breadcrumbs::for('employees', function (BreadcrumbTrail $trail) {
    $trail->parent('employee');
    $trail->push(__('breadcrumb.custom.employee.childInfo.index'), route('employees.index'));
});

// Add Employee
Breadcrumbs::for('addEmployee', function (BreadcrumbTrail $trail) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.create'), route('employees.create'));
});

// Employee Details
Breadcrumbs::for('employeeDetails', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.show', $employee));
});

// Employee Basic Info
Breadcrumbs::for('basicInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.basicInfo', $employee));
});

// Employee Additional Info
Breadcrumbs::for('additionalInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.additionalInfo', $employee));
});

// Employee Work Info
Breadcrumbs::for('workInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.workInfo', $employee));
});

// Employee Attendance
Breadcrumbs::for('attendance', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('attendance.index', $employee));
});

// Employee Attendance Log
Breadcrumbs::for('attendanceLog', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('attendance-log.index', $employee));
});

// Employee Departure Info
Breadcrumbs::for('departureInfo', function (BreadcrumbTrail $trail, Employee $employee) {
    $trail->parent('employees');
    $trail->push(__('breadcrumb.custom.employee.childInfo.details'), route('employees.departureInfo', $employee));
});

// My Profile
Breadcrumbs::for('myProfile', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.user.childInfo.profile'), route('profile.edit'));
});

// Department Menu
Breadcrumbs::for('department', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.department.parentInfo'));
});

// Department List
Breadcrumbs::for('departments', function (BreadcrumbTrail $trail) {
    $trail->parent('department');
    $trail->push(__('breadcrumb.custom.department.childInfo.index'), route('departments.index'));
});

// Add Department
Breadcrumbs::for('addDepartment', function (BreadcrumbTrail $trail) {
    $trail->parent('departments');
    $trail->push(__('breadcrumb.custom.department.childInfo.create'), route('departments.create'));
});

// Edit Department
Breadcrumbs::for('editDepartment', function (BreadcrumbTrail $trail, Department $department) {
    $trail->parent('departments');
    $trail->push(__('breadcrumb.custom.department.childInfo.edit'), route('departments.edit', $department));
});

// Department Details
Breadcrumbs::for('departmentDetails', function (BreadcrumbTrail $trail, Department $department) {
    $trail->parent('departments');
    $trail->push(__('breadcrumb.custom.department.childInfo.details'), route('departments.show', $department));
});

// Attendance Requests
Breadcrumbs::for('attendanceRequests', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.attendanceRequests.parentInfo'), route('attendance-requests.index'));
});

// Logged in Employee Attendance Logs
Breadcrumbs::for('employeeAttendanceLogs', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.loggedInEmployeeAttendance.parentInfo'), route('employee-attendance-logs.index'));
});

// Logged in Employee Attendances
Breadcrumbs::for('employeeAttendances', function (BreadcrumbTrail $trail) {
    $trail->parent('employeeAttendanceLogs');
    $trail->push(__('breadcrumb.custom.loggedInEmployeeAttendance.childInfo.attendances'), route('employee-attendances.index'));
});

// Logged in Employee Attendance Requests
Breadcrumbs::for('employeeAttendanceRequests', function (BreadcrumbTrail $trail) {
    $trail->parent('employeeAttendanceLogs');
    $trail->push(__('breadcrumb.custom.loggedInEmployeeAttendance.childInfo.attendanceRequests'), route('employee-attendance-requests.index'));
});

// Leave Menu
Breadcrumbs::for('leaveManagement', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.leave.parentInfo'));
});

// Leave Type List
Breadcrumbs::for('leaveTypes', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveManagement');
    $trail->push(__('breadcrumb.custom.leave.childInfo.leaveType.index'), route('leave-types.index'));
});

// Add Leave Type
Breadcrumbs::for('addLeaveType', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveTypes');
    $trail->push(__('breadcrumb.custom.leave.childInfo.leaveType.create'), route('leave-types.create'));
});

// Leave Type Details
Breadcrumbs::for('leaveTypeDetails', function (BreadcrumbTrail $trail, LeaveType $leaveType) {
    $trail->parent('leaveTypes');
    $trail->push(__('breadcrumb.custom.leave.childInfo.leaveType.details'), route('leave-types.show', $leaveType));
});

// Leave Type Allocation Details
Breadcrumbs::for('leaveTypeAllocationDetails', function (BreadcrumbTrail $trail, LeaveType $leaveType) {
    $trail->parent('leaveTypes');
    $trail->push(__('breadcrumb.custom.leave.childInfo.leaveType.details'), route('leave-types.leaveTypeAllocation', $leaveType));
});

// Edit Leave Type
Breadcrumbs::for('editLeaveType', function (BreadcrumbTrail $trail, LeaveType $leaveType) {
    $trail->parent('leaveTypes');
    $trail->push(__('breadcrumb.custom.leave.childInfo.leaveType.edit'), route('leave-types.edit', $leaveType));
});

// Leave Allocation List
Breadcrumbs::for('leaveAllocations', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveManagement');
    $trail->push('Leave Allocations', route('leave-allocations.index'));
});

// Add Leave Allocation
Breadcrumbs::for('addLeaveAllocation', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveAllocations');
    $trail->push('Add', route('leave-allocations.create'));
});

// Edit Leave Allocation
Breadcrumbs::for('editLeaveAllocation', function (BreadcrumbTrail $trail, LeaveAllocation $leaveAllocation) {
    $trail->parent('leaveAllocations');
    $trail->push('Edit', route('leave-allocations.edit', $leaveAllocation));
});

// Calendar Leaves List
Breadcrumbs::for('calendarLeaves', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveManagement');
    $trail->push('Calendar Leaves ', route('calendar-leaves.index'));
});

// Public Holiday List
Breadcrumbs::for('publicHolidays', function (BreadcrumbTrail $trail) {
    $trail->parent('leaveManagement');
    $trail->push('Public Holidays', route('public-holidays.index'));
});

// Add Public Holiday
Breadcrumbs::for('addPublicHoliday', function (BreadcrumbTrail $trail) {
    $trail->parent('publicHolidays');
    $trail->push('Add ', route('public-holidays.create'));
});

// Edit Public Holiday
Breadcrumbs::for('editPublicHoliday', function (BreadcrumbTrail $trail, CalendarLeave $calendarLeave) {
    $trail->parent('publicHolidays');
    $trail->push('Edit ', route('public-holidays.edit', $calendarLeave));
});

// Leave Requests
Breadcrumbs::for('leaveRequests', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Leave Requests', route('leaves.leaveRequests'));
});

// Logged in Employee Leave Requests
Breadcrumbs::for('employeeLeaveRequests', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Leave Requests', route('employee.leaveRequests'));
});

// Logged in Employee Leave List
Breadcrumbs::for('employeeLeaves', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Leave Management', route('leaves.employeeLeaves'));
});

// Subscription Menu
Breadcrumbs::for('subscription', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push(__('breadcrumb.custom.subscription.parentInfo'));
});

// Subscription List
Breadcrumbs::for('subscriptions', function (BreadcrumbTrail $trail) {
    $trail->parent('subscription');
    $trail->push(__('breadcrumb.custom.subscription.childInfo.index'), route('subscriptions.index'));
});

// Add Subscription
Breadcrumbs::for('editSubscription', function (BreadcrumbTrail $trail, Subscription $subscription) {
    $trail->parent('subscription');
    $trail->push(__('breadcrumb.custom.subscription.childInfo.edit'), route('subscriptions.edit', $subscription));
});


