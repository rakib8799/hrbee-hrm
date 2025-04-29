<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Page Title
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom page title.
    |
    */

    'custom' => [
        'home' => 'Home',
        'login' => 'Login',

        'configuration' => [
            'details' => 'Settings',
            'basicInfo' => 'Basic Information',
            'additionalInfo' => 'Additional Information',
            'contactInfo' => 'Contact Information'
        ],

        'user' => [
            'index' => 'User List',
            'create' => 'Add User',
            'show' => 'User Details',
            'edit' => 'Edit User',
            'profile' => 'My Profile'
        ],

        'role' => [
            'index' => 'Role List',
            'create' => 'Add Role',
            'show' => 'Role Details',
            'edit' => 'Edit Role'
        ],

        'branch' => [
            'index' => 'Branch List',
            'create' => 'Add Branch',
            'show' => 'Branch Details',
            'edit' => 'Edit Branch'
        ],

        'employee' => [
            'index' => [
                'attendanceLog' => 'Attendance Log List',
                'attendance' => 'Attendance List',
                'attendanceRequest' => 'Attendance Request List',
                'employee' => 'Employee List',
            ],
            'create' => 'Add Employee',
            'show' => 'Employee Details',
            'basicInfo' => 'Basic Information',
            'additionalInfo' => 'Additional Information',
            'workInfo' => 'Work Information',
            'departureInfo' => 'Departure Information'
        ],

        'jobPosition' => [
            'index' => 'Job Position List'
        ],

        'department' => [
            'index' => 'Department List',
            'create' => 'Add Department',
            'show' => 'Department Details',
            'edit' => 'Edit Department'
        ],

        'auth' => [
            'signUpConfirmation' => 'Sign Up Confirmation',
            'forgotPasswordConfirmation' => 'Forgot Password Confirmation',
            'forgotPassword' => 'Forgot Password',
            'resetPassword' => 'Reset Password'
        ],

        'leave' => [
            'leaveType' => [
                'index' => 'Leave Type List',
                'create' => 'Add Leave Type',
                'show' => 'Leave Type Details',
                'edit' => 'Edit Leave Type',
                'leaveTypeInfo' => 'Leave Type Info',
                'leaveTypeAllocationInfo' => [
                    'title' => 'Leave Type Allocation Info',
                    'show' => 'Leave Type Allocation Details'
                ]
            ]
        ],

        'subscription' => [
            'index' => 'Subscriptions',
            'edit' => 'Edit Subscription'
        ]
    ]
];
