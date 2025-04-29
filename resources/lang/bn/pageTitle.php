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
        'home' => 'হোম',
        'login' => 'লগইন',

        'configuration' => [
            'details' => 'সেটিংস',
            'basicInfo' => 'মৌলিক তথ্য',
            'additionalInfo' => 'অতিরিক্ত তথ্য',
            'contactInfo' => 'যোগাযোগের তথ্য'
        ],

        'user' => [
            'index' => 'ইউজারদের তালিকা',
            'create' => 'নতুন ইউজার',
            'show' => 'ইউজার ডিটেইলস',
            'edit' => 'এডিট ইউজার',
            'profile' => 'আমার প্রোফাইল'
        ],

        'role' => [
            'index' => 'রোলের তালিকা',
            'create' => 'নতুন রোল',
            'show' => 'রোল ডিটেইলস',
            'edit' => 'এডিট রোল'
        ],

        'branch' => [
            'index' => 'ব্রাঞ্চের তালিকা',
            'create' => 'নতুন ব্রাঞ্চ',
            'show' => 'ব্রাঞ্চ ডিটেইলস',
            'edit' => 'এডিট ব্রাঞ্চ'
        ],

        'employee' => [
            'index' => [
                'attendanceLog' => 'উপস্থিতির লগ তালিকা',
                'attendance' => 'উপস্থিতির তালিকা',
                'attendanceRequest' => 'উপস্থিতির অনুরোধের তালিকা',
                'employee' => 'কর্মচারীসমূহের তালিকা',
            ],
            'create' => 'নতুন কর্মচারী',
            'show' => 'কর্মচারীর ডিটেইলস',
            'basicInfo' => 'মৌলিক তথ্য',
            'additionalInfo' => 'অতিরিক্ত তথ্য',
            'workInfo' => 'কাজের তথ্য',
            'departureInfo' => 'চলে যাওয়ার তথ্য'
        ],

        'jobPosition' => [
            'index' => 'চাকুরীর পদসমূহের তালিকা'
        ],

        'department' => [
            'index' => 'ডিপার্টমেন্টের তালিকা',
            'create' => 'নতুন ডিপার্টমেন্ট',
            'show' => 'ডিপার্টমেন্টের ডিটেইলস',
            'edit' => 'এডিট ডিপার্টমেন্ট'
        ],

        'auth' => [
            'signUpConfirmation' => 'সাইন আপ নিশ্চিতকরণ',
            'forgotPasswordConfirmation' => 'পাসওয়ার্ড ভুলে যাওয়া নিশ্চিতকরণ',
            'forgotPassword' => 'পাসওয়ার্ড ভুলে গেছেন',
            'resetPassword' => 'রিসেট পাসওয়ার্ড'
        ],

        'leave' => [
            'leaveType' => [
                'index' => 'ছুটির ধরণের তালিকা',
                'create' => 'নতুন ছুটির ধরণ',
                'show' => 'ছুটির ধরণের ডিটেইলস',
                'edit' => 'এডিট ছুটির ধরণ',
                'leaveTypeInfo' => 'ছুটির ধরণের তথ্য',
                'leaveTypeAllocationInfo' => [
                    'title' => 'বরাদ্দকৃত ছুটির ধরণের তথ্য',
                    'show' => 'বরাদ্দকৃত ছুটির ধরণের ডিটেইলস'
                ]
            ]
        ],

        'subscription' => [
            'index' => 'সাবস্ক্রিপশন',
            'edit' => 'এডিট সাবস্ক্রিপশন'
        ]
    ]
];
