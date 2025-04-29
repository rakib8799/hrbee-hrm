<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Message Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'internal_server_error' => 'An error occurred on the server. Please try again later',
    'otp_sent' => 'Verification code has been sent',
    'otp_resent' => 'The verification code has been re-sent',
    'otp_sending_blocked' => 'Your number has been blocked for 5 minutes. Please wait',
    'user_inactive' => 'Your account has been deactivated. Please contact the administrator',
    'user_deleted' => 'Your information has been deleted',


    /*
    |--------------------------------------------------------------------------
    | Custom Message Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom messages.
    |
    */

    'custom' => [
        'configuration' => [
            'update' => [
                'basicInfo' => [
                    'success' => 'Company\'s basic information has been updated successfully',
                    'error' => 'Company\'s basic information could not be updated'
                ],
                'additionalInfo' => [
                    'success' => 'Company\'s additional information has been updated successfully',
                    'error' => 'Company\'s additional information could not be updated'
                ],
                'contactInfo' => [
                    'success' => 'Company\'s contact information has been updated successfully',
                    'error' => 'Company\'s contact information could not be updated'
                ]
            ]
        ],

        'user' => [
            'store' => [
                'success' => 'New user created successfully',
                'error' => 'New user could not be created'
            ],
            'update' => [
                'basic' => [
                    'success' => 'User\'s information updated successfully',
                    'error' => 'User could not be updated'
                ],
                'profile' => [
                    'success' => 'User\'s profile updated successfully',
                    'error' => 'User\'s profile could not be updated'
                ],
                'updateDetails' => [
                    'success' => 'Details of user updated successfully',
                    'error' => 'Details of user could not be updated'
                ],
                'updateEmail' => [
                    'success' => 'Email of user updated successfully',
                    'error' => 'Email of user could not be updated'
                ],
                'updateRoles' => [
                    'success' => 'Roles of user updated successfully',
                    'error' => 'Roles of user could not be updated'
                ],
                'updatePassword' => [
                    'success' => 'Password updated successfully',
                    'error' => 'Password could not be updated'
                ]
            ],
            'destroy' => [
                'success' => 'User deleted successfully',
                'error' => 'User could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'User is activated',
                'deactivate' => 'User is deactivated',
                'error' => 'User status could not be changed'
            ]
        ],

        'role' => [
            'store' => [
                'success' => 'New role created successfully',
                'error' => 'New role could not be created'
            ],
            'update' => [
                'success' => 'Role and permission updated successfully',
                'error' => 'Role and permission could not be updated'
            ],
            'destroy' => [
                'basic' => [
                    'success' => 'Role and permission deleted successfully',
                    'error' => 'Role and permission could not be deleted',
                ],
                'removeUserFromRole' => [
                    'success' => 'User removed from role successfully',
                    'error' => 'User removed from role could not be deleted'
                ]
            ],
            'changeStatus' => [
                'activate' => 'Role is activated',
                'deactivate' => 'Role is deactivated',
                'error' => 'Super admin status can not be changed'
            ]
        ],

        'branch' => [
            'store' => [
                'success' => 'New branch created successfully',
                'error' => 'New branch could not be created'
            ],
            'update' => [
                'success' => 'Branch updated successfully',
                'error' => 'Branch could not be updated'
            ],
            'destroy' => [
                'success' => 'Branch deleted successfully',
                'error' => 'Branch could not be deleted'
            ],
            'employeeOptions' => 'Get employee selection by branch name',
            'changeStatus' => [
                'activate' => 'Branch is activated',
                'deactivate' => 'Branch is deactivated'
            ]
        ],

        'employee' => [
            'attendanceLog' => [
                'store' => [
                    'success' => 'Employee\'s attendance log created successfully',
                    'error' => 'Employee\'s attendance log could not be created'
                ],
                'checkout' => [
                    'success' => 'Employee checked out successfully',
                    'error' => 'Employee could not be checked out'
                ]
            ],
            'attendanceRequest' => [
                'store' => [
                    'success' => 'Successfully requested manager to provide missing attendance',
                    'error' => 'The manager could not be requested to provide missing attendance'
                ]
            ]
        ],

        'employeeManagement' => [
            'attendanceLog' => [
                'store' => [
                    'success' => 'Attendance log created successfully',
                    'error' => 'Attendance log could not be created'
                ],
                'update' => [
                    'success' => 'Attendance log updated successfully',
                    'error' => 'Attendance log could not be updated'
                ],
                'destroy' => [
                    'success' => 'Attendance log deleted successfully',
                    'error' => 'Attendance log could not be deleted'
                ]
            ],
            'attendanceRequest' => [
                'approveAttendanceRequest' => [
                    'success' => 'Attendance request approved successfully',
                    'error' => 'Attendance request could not be approved'
                ],
                'declineAttendanceRequest' => [
                    'success' => 'Attendance request declined successfully',
                    'error' => 'Attendance request could not be declined'
                ]
            ],
            'employee' => [
                'store' => [
                    'success' => 'New employee created successfully',
                    'error' => 'New employee could not be created'
                ],
                'destroy' => [
                    'success' => 'Employee deleted successfully',
                    'error' => 'Employee could not be deleted'
                ],
                'update' => [
                    'basicInfo' => [
                        'success' => 'Employee\'s basic information has been updated successfully',
                        'error' => 'Employee\'s basic information could not be updated'
                    ],
                    'additionalInfo' => [
                        'success' => 'Employee\'s additional information has been updated successfully',
                        'error' => 'Employee\'s additional information could not be updated'
                    ],
                    'workInfo' => [
                        'success' => 'Employee\'s work information has been updated successfully',
                        'error' => 'Employee\'s work information could not be updated'
                    ],
                    'departureInfo' => [
                        'success' => 'Employee departed successfully',
                        'error' => 'Employee could not be departed'
                    ],
                    'rejoinInfo' => [
                        'success' => 'Employee rejoined successfully',
                        'error' => 'Employee could not be rejoined'
                    ]
                ],
                'changeStatus' => [
                    'activate' => 'Employee is activated',
                    'deactivate' => 'Employee is deactivated',
                    'error' => 'Employee status could not be changed'
                ]
            ]
        ],

        'jobPosition' => [
            'store' => [
                'success' => 'New job position created successfully',
                'error' => 'New job position could not be created'
            ],
            'update' => [
                'success' => 'Job position updated successfully',
                'error' => 'Job position could not be updated'
            ],
            'destroy' => [
                'success' => 'Job position deleted successfully',
                'error' => 'Job position could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'Job position is activated',
                'deactivate' => 'Job position is deactivated',
                'error' => 'Job position status could not be changed'
            ]
        ],

        'department' => [
            'store' => [
                'success' => 'New department created successfully',
                'error' => 'New department could not be created'
            ],
            'update' => [
                'success' => 'Department updated successfully',
                'error' => 'Department could not be updated'
            ],
            'destroy' => [
                'success' => 'Department deleted successfully',
                'error' => 'Department could not be deleted'
            ],
            'changeStatus' => [
                'activate' => 'Department is activated',
                'deactivate' => 'Department is deactivated'
            ]
        ],

        'leave' => [
            'leaveType' => [
                'store' => [
                    'success' => 'New leave type created successfully',
                    'error' => 'New leave type could not be created'
                ],
                'update' => [
                    'success' => 'Leave type updated successfully',
                    'error' => 'Leave type could not be updated'
                ],
                'destroy' => [
                    'success' => 'Leave type deleted successfully',
                    'error' => 'Leave type could not be deleted'
                ],
                'changeStatus' => [
                    'activate' => 'Leave type is activated',
                    'deactivate' => 'Leave type is deactivated',
                    'error' => 'Leave type status could not be changed'
                ]
            ]
        ],

        'subscription' => [
            'update' => [
                'success' => 'Subscription updated successfully',
                'error' => 'Your current subscription plan is not available anymore'
            ],
            'renew' => [
                'success' => 'Subscription renewed successfully',
                'error' => 'Subscription could not be renewed',
            ]
        ]
    ]
];
