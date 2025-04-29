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
            'parentInfo' => 'সেটিংস',
            'childInfo' => [
                'basicInfo' => 'মৌলিক তথ্য',
                'additionalInfo' => 'অতিরিক্ত তথ্য',
                'contactInfo' => 'যোগাযোগের তথ্য'
            ]
        ],

        'user' => [
            'parentInfo' => 'ইউজার ম্যানেজমেন্ট',
            'childInfo' => [
                'index' => 'ইউজার',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'ডিটেইলস',
                'profile' => 'প্রোফাইল'
            ]
        ],

        'role' => [
            'childInfo' => [
                'index' => 'রোল',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'ডিটেইলস'
            ]
        ],

        'branch' => [
            'parentInfo' => 'ব্রাঞ্চ',
            'childInfo' => [
                'index' => 'ব্রাঞ্চসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'ডিটেইলস'
            ]
        ],

        'employee' => [
            'parentInfo' => 'কর্মচারী',
            'childInfo' => [
                'index' => 'কর্মচারীসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'ডিটেইলস'
            ]
        ],

        'attendanceRequests' => [
            'parentInfo' => 'উপস্থিতির অনুরোধ'
        ],

        'loggedInEmployeeAttendance' => [
            'parentInfo' => 'উপস্থিতির লগ',
            'childInfo' => [
                'attendances' => 'উপস্থিতি',
                'attendanceRequests' => 'উপস্থিতির অনুরোধ'
            ]
        ],

        'jobPosition' => [
            'childInfo' => 'চাকুরীর পদসমূহ'
        ],

        'department' => [
            'parentInfo' => 'ডিপার্টমেন্ট',
            'childInfo' => [
                'index' => 'ডিপার্টমেন্টসমূহ',
                'create' => 'নতুন',
                'edit' => 'এডিট',
                'details' => 'ডিটেইলস'
            ]
        ],

        'leave' => [
            'parentInfo' => 'লিভ ম্যানেজমেন্ট',
            'childInfo' => [
                'leaveType' => [
                    'index' => 'ছুটির ধরণসমূহ',
                    'create' => 'নতুন',
                    'edit' => 'এডিট',
                    'details' => 'ডিটেইলস'
                ]
            ]
        ],

        'subscription' => [
            'parentInfo' => 'সাবস্ক্রিপশন',
            'childInfo' => [
                'index' => 'সাবস্ক্রিপশনসমূহ',
                'edit' => 'এডিট'
            ]
        ]
    ]
];
