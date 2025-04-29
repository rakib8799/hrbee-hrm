<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Breadcrumbs
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom breadcrumbs.
    |
    */

    'custom' => [
        'configuration' => [
            'parentInfo' => 'Settings',
            'childInfo' => [
                'basicInfo' => 'Basic Information',
                'additionalInfo' => 'Additional Information',
                'contactInfo' => 'Contact Information'
            ]
        ],

        'user' => [
            'parentInfo' => 'User Management',
            'childInfo' => [
                'index' => 'Users',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details',
                'profile' => 'Profile'
            ]
        ],

        'role' => [
            'childInfo' => [
                'index' => 'Roles',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'branch' => [
            'parentInfo' => 'Branch',
            'childInfo' => [
                'index' => 'Branches',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'employee' => [
            'parentInfo' => 'Employee',
            'childInfo' => [
                'index' => 'Employees',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'attendanceRequests' => [
            'parentInfo' => 'Attendance Requests'
        ],

        'loggedInEmployeeAttendance' => [
            'parentInfo' => 'Attendance Logs',
            'childInfo' => [
                'attendances' => 'Attendances',
                'attendanceRequests' => 'Attendance Requests'
            ]
        ],

        'jobPosition' => [
            'childInfo' => 'Job Positions'
        ],

        'department' => [
            'parentInfo' => 'Department',
            'childInfo' => [
                'index' => 'Departments',
                'create' => 'Add',
                'edit' => 'Edit',
                'details' => 'Details'
            ]
        ],

        'leave' => [
            'parentInfo' => 'Leave Management',
            'childInfo' => [
                'leaveType' => [
                    'index' => 'Leave Types',
                    'create' => 'Add',
                    'edit' => 'Edit',
                    'details' => 'Details'
                ]
            ]
        ],

        'subscription' => [
            'parentInfo' => 'Subscription',
            'childInfo' => [
                'index' => 'Subscriptions',
                'edit' => 'Edit'
            ]
        ]
    ]
];
