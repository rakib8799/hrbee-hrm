<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'phone' => ':attribute ফিল্ডে একটি ভুল নাম্বার রয়েছে ।',
    'accepted' => ':attribute গ্রহণ করা আবশ্যক।',
    'active_url' => ':attribute বৈধ URL নয়।',
    'after' => ':attribute-এর পর তারিখ থাকতে হবে :date.',
    'after_or_equal' => ':attribute-এ পরে অথবা সমান একটি তারিখ থাকতে হবে :date.',
    'alpha' => ':attribute-এ শুধুমাত্র লেটার থাকতে পারে।',
    'alpha_dash' => ':attribute-এ শুধুমাত্র লেটার, নম্বর, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'alpha_num' => ':attribute-এ শুধুমাত্র লেটার ও নম্বর থাকতে পারে।',
    'array' => ':attribute একটি সিরিয়ালে হতে হবে ',
    'before' => ':attribute-এ আগের তারিখ থাকতে হবে :date.',
    'before_or_equal' => ':attribute-এ আগের অথবা সমান একটি তারিখ থাকতে হবে :date.',
    'between' => [
        'numeric' => ':attribute অবশ্যই :min ও :max এর মধ্যে হতে হবে।',
        'file' => ':attribute অবশ্যই :min ও :max kilobyte-এর মধ্যে হতে হবে৷',
        'string' => ':attribute অবশ্যই :min ও :max ক্যারেক্টারের মধ্যে হতে হবে৷',
        'array' => ':attribute অবশ্যই :min ও :max আইটেমের মধ্যে হতে হবে',
    ],
    'boolean' => ':attribute ফিল্ড সত্য বা মিথ্যা হতে হবে।',
    'confirmed' => ':attribute কনফরমেশন মেলেনি।',
    'date' => ':attribute তারিখ বৈধ নয়।',
    'date_equals' => ':attribute একটি তারিখের সমান হতে হবে :date.',
    'date_format' => ':attribute format :format-এর সাথে মেলেনি। ',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute হতে হবে :digits digits.',
    'digits_between' => ':attribute অবশ্যই :min ও :max digits-এর মধ্যে হতে হবে৷',
    'dimensions' => ':attribute-এর ছবির ডায়মেনশন বৈধ নয়।',
    'distinct' => ':attribute ফিল্ডের duplicate value আছে। ',
    'email' => ':attribute-এ একটি বৈধ ইমেইল ঠিকানা হতে হবে। ',
    'ends_with' => ':attribute নিম্নলিখিত একটি দিয়ে শেষ হতে হবে: :values.',
    'exists' => ':attribute বৈধ নয়',
    'file' => ':attribute একটি ফাইলে হতে হবে।',
    'filled' => ':attribute ফিল্ডের একটি মান থাকতে হবে।',
    'gt' => [
        'numeric' => ':attribute :value-এর থেকে বড় হতে হবে। ',
        'file' => ':attribute :value kilobyte-এর চেয়ে বেশি হতে হবে। ',
        'string' => ':attribute :value character-এর চেয়ে বড় হতে হবে। ',
        'array' => ':attribute-এর চেয়ে বেশি থাকতে হবে :value items।',
    ],
    'gte' => [
        'numeric' => ':attribute-এর চেয়ে বড় বা সমান হতে হবে :value.',
        'file' => ':attribute-এর চেয়ে বড় বা সমান হতে হবে :value kilobytes.',
        'string' => ':attribute-এর চেয়ে বড় বা সমান হতে হবে :value characters.',
        'array' => ':attribute-এ থাকতে হবে :value items অথবা বেশি.',
    ],
    'image' => ':attribute-এর ছবি থাকতে হবে।',
    'in' => ':attribute বৈধ নয়।',
    'in_array' => ':attribute ফিল্ড :other-এর মধ্যে বিদ্যমান নেই ',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ IP ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute বৈধ JSON স্ট্রিং হতে হবে',
    'lt' => [
        'numeric' => ':attribute :value-এর চেয়ে কম হতে হবে।',
        'file' => ':attribute :value kilobytes-এর চেয়ে কম হতে হবে।',
        'string' => ':attribute :value characters-এর চেয়ে কম হতে হবে ',
        'array' => ':attribute :value items-এর চেয়ে কম হতে হবে।',
    ],
    'lte' => [
        'numeric' => ':attribute :value-এর চেয়ে কম বা সমান হতে হবে.',
        'file' => ':attribute :value kilobytes-এর চেয়ে কম বা সমান হতে হবে।',
        'string' => ':attribute :value characters-এর চেয়ে কম বা সমান হতে হবে .',
        'array' => ':attribute :value items-চেয়ে বেশি থাকতে হবে না। ',
    ],
    'max' => [
        'numeric' => ':attribute :max-এর থেকে বেশি নাও হতে পারে। ',
        'file' => ':attribute :max kilobytes-এর থেকে বেশি নাও হতে পারে।',
        'string' => ':attribute :max characters-এর থেকে বেশি নাও হতে পারে।',
        'array' => ':attribute :max items-এর থেকে বেশি নাও হতে পারে।',
    ],
    'mimes' => ':attribute ফাইল টাইপ হতে হবে: :values.',
    'mimetypes' => ':attribute ফাইল টাইপ হতে হবে: :values.',
    'min' => [
        'numeric' => ':attribute কমপক্ষে হতে হবে :min.',
        'file' => ':attribute কমপক্ষে হতে হবে :min kilobytes.',
        'string' => ':attribute কমপক্ষে হতে হবে :min characters.',
        'array' => ':attribute কমপক্ষে হতে হবে :min items.',
    ],
    'not_in' => ':attribute বৈধ নয়।',
    'not_regex' => ':attribute ফরমেট বৈধ নয়',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => 'পাসওয়ার্ডটি ভুল।',
    'present' => ':attribute ফিল্ড উপস্থিত থাকতে হবে।',
    'regex' => ':attribute ফরমেটটি বৈধ নয়।',
    'required' => ':attribute ফিল্ড প্রয়োজন।',
    'required_if' => ':attribute ফিল্ড প্রয়োজন যখন :other হলো :value',
    'required_unless' => ':attribute ফিল্ড প্রয়োজন যদি না :other-এর মধ্যে :valuesথাকে।',
    'required_with' => ':attribute ফিল্ড প্রয়োজন হয় যখন :values উপস্থিত থাকে। ',
    'required_with_all' => ':attribute ফিল্ড প্রয়োজন হয় যখন :values উপস্থিত থাকে।',
    'required_without' => ':attribute ফিল্ড প্রয়োজন হয় যখন :values উপস্থিত না থাকে।',
    'required_without_all' => ':attribute ফিল্ড প্রয়োজন হয় যখন কোনো :values উপস্থিত থাকে না। ',
    'same' => ':attribute এবং :other অবশ্যই মিলতে হবে। ',
    'size' => [
        'numeric' => ':attribute হতে হবে :size.',
        'file' => ':attribute হতে হবে :size kilobytes.',
        'string' => ':attribute হতে হবে :size characters.',
        'array' => ':attribute হতে হবে :size items.',
    ],
    'starts_with' => ':attribute নিম্নলিখিত একটি দিয়ে শুরু করা আবশ্যক: :values.',
    'string' => ':attribute স্ট্রিং হতে হবে।',
    'timezone' => ':attribute অবশ্যই একটি বৈধ জোন হতে হবে।',
    'unique' => ':attribute ইতোমধ্যে নেওয়া হয়েছে ',
    'uploaded' => ':attribute আপলোড হতে ব্যর্থ হয়েছে.',
    'url' => ':attribute ফরম্যাট বৈধ নয়',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে। ',


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [

        'dob' => [
            'date_format' => 'তারিখ yyyy-mm-dd ফরম্যাটের সাথে মেলে না।',
        ],

        'mobile' => [
            'invalid_format' => 'কনটাক্ট নম্বরের ফরম্যাট অবৈধ ',
        ],

        'no_numeric' => 'কোনো সংখ্যাজাতীয় (0-৯) মান থাকতে পারে না',
        'incorrect_password' => 'বর্তমান পাসওয়ার্ডটি সঠিক নয়।',

        'configuration' => [
            'name' => [
                'string' => 'প্রতিষ্ঠানের নাম অবশ্যই স্ট্রিং হতে হবে।'
            ],
            'industry' => [
                'string' => 'শিল্পখাতের নাম অবশ্যই স্ট্রিং হতে হবে'
            ],
            'other_language_id' => [
                'exists' => 'নির্বাচিত ভাষাটি পাওয়া যায়নি'
            ],
            'timezone_id' => [
                'exists' => 'নির্বাচিত টাইমজোনটি পাওয়া যায়নি'
            ],
            'date_format' => [
                'string' => 'তারিখ ফরম্যাট অবশ্যই স্ট্রিং হতে হবে',
                'in' => 'নির্বাচিত তারিখ ফরম্যাটটি সঠিক নয়'
            ],
            'is_roster' => [
                'boolean' => 'রোস্টার অবশ্যই সত্য বা মিথ্যা হতে হবে'
            ],
            'week_start_day' => [
                'string' => 'সপ্তাহের শুরুর দিনটি অবশ্যই স্ট্রিং হতে হবে',
                'in' => 'নির্বাচিত সপ্তাহের শুরুর দিনটি সঠিক নয়'
            ],
            'weekends' => [
                'array' => 'সাপ্তাহিক ছুটির দিন অবশ্যই অ্যারে হতে হবে'
            ],
            'lunch_break_time' => [
                'numeric' => 'লাঞ্চ বিরতির সময় অবশ্যই সংখ্যা হতে হবে'
            ],
            'website' => [
                'url' => 'একটি ভ্যালিড ইউ.আর.এল প্রদান করুন'
            ],
            'organization_number' => [
                'numeric' => 'প্রতিষ্ঠানের নম্বর অবশ্যই সংখ্যা হতে হবে',
                'unique' => 'ইতিমধ্যে প্রতিষ্ঠানের নম্বর নেওয়া হয়েছে'
            ],
            'email' => [
                'email' => 'একটি ভ্যালিড ইমেইল প্রদান করুন',
                'unique' => 'ইতিমধ্যে ইমেলটি নেওয়া হয়েছে'
            ],
            'admin_email' => [
                'email' => 'একটি ভ্যালিড ইমেইল প্রদান করুন',
                'unique' => 'অ্যাডমিন ইমেইল ইতিমধ্যে নেওয়া হয়েছে'
            ],
            'telephone' => [
                'string' => 'টেলিফোন নম্বর অবশ্যই স্ট্রিং হতে হবে'
            ],
            'country_id' => [
                'exists' => 'নির্বাচিত দেশটি পাওয়া যায়নি'
            ]
        ],

        'user' => [
            'name' => [
                'required' => 'নাম প্রয়োজন'
            ],
            'email' => [
                'required' => 'ইমেইল প্রয়োজন',
                'email' => 'একটি ভ্যালিড ইমেইল প্রদান করুন',
                'unique' => 'ইতিমধ্যে ইমেইলটি নেওয়া হয়েছে'
            ],
            'current_password' => [
                'required' => 'বর্তমান পাসওয়ার্ড প্রয়োজন',
                'current_password' => 'বর্তমান পাসওয়ার্ডটি ভুল হয়েছে'
            ],
            'password' => [
                'required' => 'নতুন পাসওয়ার্ড প্রয়োজন',
                'min' => 'নতুন পাসওয়ার্ড কমপক্ষে ৮ ক্যারেক্টারের হতে হবে'
            ],
            'password_confirmation' => [
                'required' => 'কনফার্মেশন পাসওয়ার্ড প্রয়োজন',
                'same' => 'কনফার্মেশন পাসওয়ার্ডটি মেলেনি।'
            ]
        ],

        'role' => [
            'name' => [
                'required' => 'নাম প্রয়োজন',
                'unique' => 'নাম ইতিমধ্যেই বিদ্যমান'
            ],
            'permission_ids' => [
                'array' => 'রোল অবশ্যই অ্যারে হতে হবে'
            ],
            'permission_id' => [
                'required' => 'রোল প্রয়োজন',
                'exists' => 'নির্বাচিত রোলটি পাওয়া যায়নি'
            ],
            'user_id' => [
                'required' => 'ইউজার প্রয়োজন',
                'exists' => 'নির্বাচিত ইউজারকে পাওয়া যায়নি'
            ],
            'role_id' => [
                'required' => 'রোল প্রয়োজন',
                'exists' => 'নির্বাচিত রোলটি পাওয়া যায়নি'
            ]
        ],

        'branch' => [
            'name' => [
                'required' => 'ব্রাঞ্চের নাম প্রয়োজন',
                'unique' => 'ব্রাঞ্চের নাম ইতিমধ্যেই বিদ্যমান',
                'string' => 'ব্রাঞ্চের নাম অবশ্যই স্ট্রিং হতে হবে'
            ],
            'manager_id' => [
                'exists' => 'নির্বাচিত ম্যানেজারকে পাওয়া যায়নি'
            ],
            'branch_code' => [
                'string' => 'ব্রাঞ্চের কোড অবশ্যই স্ট্রিং হতে হবে',
                'max' => 'ব্রাঞ্চের কোড ১০ অক্ষরের বেশি হতে পারে না'
            ],
            'phone_number' => [
                'string' => 'ফোন নম্বর অবশ্যই স্ট্রিং হতে হবে'
            ],
            'geo_location' => [
                'string' => 'ভূ-অবস্থান অবশ্যই স্ট্রিং হতে হবে'
            ],
            'day_off' => [
                'json' => 'ছুটির দিন অবশ্যই একটি ভ্যালিড জেসন স্ট্রিং হতে হবে'
            ]
        ],

        'employee' => [
            'attendanceLog' => [
                'punch_type' => [
                    'required' => 'পাঞ্চ টাইপ প্রয়োজন',
                    'in' => 'পাঞ্চ টাইপ অবশ্যই ইন, আউট, ব্রেক - এর যেকোনো একটি হতে হবে'
                ],
                'punch_time' => [
                    'required' => 'পাঞ্চ টাইম প্রয়োজন',
                ],
                'note' => [
                    'string' => 'নোট অবশ্যই স্ট্রিং হতে হবে'
                ],
                'punchBeforeIn' => 'নতুন দিনের প্রথম পাঞ্চের ক্ষেত্রে, আপনি পাঞ্চ-ইন করা ছাড়া পাঞ্চ-ব্রেক / আউট করতে পারবেন না',
                'isDuplicateDate' => 'আপনি ইতিমধ্যেই এই তারিখের জন্য উপস্থিতি সম্পন্ন করেছেন',
                'noEntriesAfterOut' => 'একই দিনে পাঞ্চ-আউটের পর আর কোনো প্রকার প্রবেশের অনুমতি নেই',
                'punchTimeOutOfSequence' => 'প্রতিটি পাঞ্চের সময় পূর্ববর্তী পাঞ্চের সময়ের পরে হতে হবে।',
                'punchInWithoutBreak' => 'আপনি পাঞ্চ-ব্রেক ছাড়া আবার পাঞ্চ-ইন করতে পারবেন না',
                'punchAfterBreak' => 'আপনি পাঞ্চ-ব্রেকের পর পাঞ্চ-ইন করা ছাড়া পাঞ্চ-ব্রেক / আউট করতে পারবেন না',
                'workFromFirstIn' => 'নতুন দিনের প্রথম পাঞ্চের জন্য কর্মক্ষেত্রের উল্লেখ থাকা প্রয়োজন',
                'alreadyEnteredWorkFrom' => 'আপনি ইতিমধ্যে দিনের শুরুতে কর্মক্ষেত্রে প্রবেশ করেছেন',
                'workFromType' => 'কর্মক্ষেত্রের অবস্থান অবশ্যই বাড়ি থেকে, অফিস থেকে, যেকোনো জায়গা থেকে - এর মধ্যে একটি হতে হবে'
            ],
            'checkout' => [
                'punch_type' => [
                    'required' => 'পাঞ্চ টাইপ প্রয়োজন'
                ],
                'punch_time' => [
                    'required' => 'চেক-আউট টাইম প্রয়োজন',
                ],
                'outGreaterThanIn' => 'চেক-আউট টাইম চেক-ইন টাইমের চেয়ে বেশি হতে হবে'
            ],
            'missingAttendance' => [
                'attendance_date' => [
                    'required' => 'উপস্থিতির তারিখ প্রয়োজন'
                ],
                'check_in' => [
                    'required' => 'চেক-ইন টাইম প্রয়োজন'
                ],
                'check_out' => [
                    'required' => 'চেক-আউট টাইম প্রয়োজন'
                ],
                'work_from' => [
                    'required' => 'কর্মক্ষেত্র প্রয়োজন',
                    'in' => 'কর্মক্ষেত্র অবশ্যই বাড়ি থেকে, অফিস থেকে, যেকোনো জায়গা থেকে - এর যেকোনো একটি হতে হবে'
                ],
                'outLaterThanIn' => 'চেক-আউটের টাইম অবশ্যই চেক-ইন টাইমের চেয়ে পরে হতে হবে',
                'attendanceAlreadyExists' => 'উপস্থিতি ইতিমধ্যে জন্য বিদ্যমান ',
                'missingAttendanceNotRecorded' => 'কর্মচারীর যোগদানের তারিখের আগে একটি তারিখের জন্য অনুপস্থিত উপস্থিতি রেকর্ড করা যাবে না',
                'missingAttendanceCanNotAdded' => 'অনুপস্থিত উপস্থিতি আজকের বা ভবিষ্যতের তারিখের জন্য যোগ করা যাবে না',
                'alreadyCreatedMissingAttendance' => 'আপনি ইতিমধ্যে অনুপস্থিত উপস্থিতির অনুরোধ করেছেন '
            ],
            'employeeNotJoinedYet' => 'কর্মচারী এখনও যোগদান করেননি, তাই উপস্থিতি যোগ করা যাবে না'
        ],

        'employeeManagement' => [
            'attendanceLog' => [
                'staff_id' => [
                    'required' => 'স্টাফ আইডি প্রয়োজন'
                ],
                'punch_type' => [
                    'required' => 'পাঞ্চ টাইপ প্রয়োজন',
                    'in' => 'পাঞ্চ টাইপ অবশ্যই ইন, আউট, ব্রেক - এর যেকোনো একটি হতে হবে'
                ],
                'punch_time' => [
                    'required' => 'পাঞ্চ টাইম প্রয়োজন',
                ],
                'work_from' => [
                    'in' => 'কর্মক্ষেত্র অবশ্যই বাড়ি থেকে, অফিস থেকে, যেকোনো জায়গা থেকে - এর যেকোনো একটি হতে হবে'
                ]
            ],
            'employeeDocument' => [
                'name' => [
                    'required' => 'নাম প্রয়োজন'
                ],
                'file_path' => [
                    'required' => 'ফাইল পাথ প্রয়োজন'
                ],
                'employee_id' => [
                    'required' => 'কর্মচারীকে প্রয়োজন',
                    'exists' => 'নির্বাচিত কর্মচারী বিদ্যমান নেই'
                ],
                'source' => [
                    'required' => 'উৎস প্রয়োজন',
                    'in' => 'উৎস অবশ্যই কর্মচারী, অফিস - এর যেকোনো একটি হতে হবে'
                ],
                'document_type_id' => [
                    'required' => 'ডকুমেন্টের টাইপ প্রয়োজন',
                    'exists' => 'নির্বাচিত ডকুমেন্টটি বিদ্যমান নেই'
                ]
            ],
            'employee' => [
                'first_name' => [
                    'required' => 'নামের প্রথম অংশ প্রয়োজন',
                    'string' => 'নামের প্রথম অংশ অবশ্যই স্ট্রিং হতে হবে'
                ],
                'last_name' => [
                    'required' => 'নামের শেষ অংশ প্রয়োজন',
                    'string' => 'নামের শেষ অংশ অবশ্যই স্ট্রিং হতে হবে'
                ],
                'email' => [
                    'required' => 'ইমেইল প্রয়োজন',
                    'email' => 'একটি ভ্যালিড ইমেইল প্রদান করুন',
                    'unique' => 'ইতিমধ্যে ইমেলটি নেওয়া হয়েছে'
                ],
                'staff_id' => [
                    'required' => 'স্টাফ আইডি প্রয়োজন',
                    'unique' => 'ইতিমধ্যে স্টাফ আইডিটি নেওয়া হয়েছে'
                ],
                'social_security_number' => [
                    'string' => 'সামাজিক নিরাপত্তা নম্বর অবশ্যই স্ট্রিং হতে হবে'
                ],
                'mobile_number' => [
                    'required' => 'মোবাইল নম্বর প্রয়োজন',
                    'string' => 'মোবাইল নম্বর অবশ্যই স্ট্রিং হতে হবে',
                    'size' => 'মোবাইল নম্বর অবশ্যই ১১ ডিজিটের হতে হবে',
                    'regex' => 'মোবাইল নম্বর শুধু ডিজিট হতে পারবে'
                ],
                'dob' => [
                    'required' => 'জন্ম তারিখ প্রয়োজন',
                    'date' => 'জন্ম তারিখ অবশ্যই ভ্যালিড তারিখ হতে হবে'
                ],
                'blood_group' => [
                    'string' => 'রক্তের গ্রুপ অবশ্যই স্ট্রিং হতে হবে',
                    'in' => 'রক্তের গ্রুপ অবশ্যই এ+, এ-, বি+, বি-, এবি+, এবি-, ও+, ও- - এর যেকোনো একটি হতে হবে'
                ],
                'gender' => [
                    'string' => 'লিঙ্গ অবশ্যই স্ট্রিং হতে হবে',
                    'in' => 'লিঙ্গ অবশ্যই পুরুষ, মহিলা, অন্যান্য - এর যেকোনো একটি হতে হবে'
                ],
                'marital_status' => [
                    'string' => 'বৈবাহিক অবস্থা অবশ্যই স্ট্রিং হতে হবে',
                    'in' => 'বৈবাহিক অবস্থা অবশ্যই অবিবাহিত, বিবাহিত, বৈধ-সহবাসকারী, বিধবা, তালাকপ্রাপ্ত - এর যেকোনো একটি হতে হবে'
                ],
                'additionalInfo' => [
                    'user_id' => [
                        'exists' => 'নির্বাচিত ব্যবহারকারী বিদ্যমান নেই'
                    ],
                    'permanent_address' => [
                        'string' => 'স্থায়ী ঠিকানা অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'country_id' => [
                        'exists' => 'নির্বাচিত দেশ বিদ্যমান নেই'
                    ],
                    'emergency_contact_name' => [
                        'string' => 'জরুরী যোগাযোগের নাম অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'emergency_contact_relation' => [
                        'string' => 'জরুরী যোগাযোগের সম্পর্ক অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'emergency_contact_number' => [
                        'string' => 'জরুরী যোগাযোগের নম্বর অবশ্যই স্ট্রিং হতে হবে'
                    ]
                ],
                'workInfo' => [
                    'job_title' => [
                        'string' => 'কাজের শিরোনাম অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'job_positions' => [
                        'required' => 'চাকরির পদ প্রয়োজন',
                        'exists' => 'চাকরির পদ বিদ্যমান নেই'
                    ],
                    'department_id' => [
                        'exists' => 'নির্বাচিত ডিপার্টমেন্ট বিদ্যমান নেই'
                    ],
                    'branch_id' => [
                        'required' => 'ব্রাঞ্চ প্রয়োজন',
                        'exists' => 'নির্বাচিত ব্রাঞ্চ বিদ্যমান নেই'
                    ],
                    'manager_id' => [
                        'exists' => 'নির্বাচিত ম্যানেজার বিদ্যমান নেই'
                    ],
                    'leave_manager_id' => [
                        'exists' => 'নির্বাচিত লিভ ম্যানেজার বিদ্যমান নেই'
                    ],
                    'salary' => [
                        'numeric' => 'বেতন অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'hourly_rate' => [
                        'numeric' => 'ঘণ্টাপ্রতি রেট অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'hour_limit_weekly' => [
                        'integer' => 'সাপ্তাহিক ঘন্টা লিমিট অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'over_time_rate' => [
                        'numeric' => 'ওভারটাইম রেট অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'over_time_limit' => [
                        'integer' => 'ওভারটাইম লিমিট অবশ্যই পূর্ণসংখ্যা হতে হবে'
                    ],
                    'joining_date' => [
                        'required' => 'যোগদানের তারিখ প্রয়োজন',
                        'date' => 'যোগদানের তারিখ অবশ্যই একটি ভ্যালিড তারিখ হতে হবে'
                    ],
                    'linkedin_profile' => [
                        'url' => 'লিঙ্কডিন প্রোফাইল অবশ্যই একটি ভ্যালিড ইউ.আর.এল হতে হবে'
                    ],
                    'etin' => [
                        'numeric' => 'ই-টিন নম্বর অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'passport_number' => [
                        'string' => 'পাসপোর্ট নম্বর অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'visa_number' => [
                        'string' => 'ভিসা নম্বর অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'visa_expire_date' => [
                        'date' => 'ভিসার মেয়াদ শেষ হওয়ার তারিখ অবশ্যই একটি ভ্যালিড তারিখ হতে হবে'
                    ],
                    'work_permit_number' => [
                        'numeric' => 'ওয়ার্ক পারমিট নম্বর অবশ্যই সংখ্যা হতে হবে'
                    ],
                    'work_permit_expiration_date' => [
                        'date' => 'ওয়ার্ক পারমিটের মেয়াদ শেষ হওয়ার তারিখ অবশ্যই একটি ভ্যালিড তারিখ হতে হবে'
                    ],
                    'departure_date' => [
                        'date' => 'চলে যাওয়ার তারিখ অবশ্যই একটি ভ্যালিড তারিখ হতে হবে'
                    ],
                    'departure_reason' => [
                        'string' => 'চলে যাওয়ার কারণ অবশ্যই স্ট্রিং হতে হবে'
                    ],
                    'is_nfc_card_issued' => [
                        'boolean' => 'এন.এফ.সি কার্ড জারি করতে অবশ্যই সত্য বা মিথ্যা হতে হবে'
                    ],
                    'is_geo_fencing_enabled' => [
                        'boolean' => 'জিও ফেন্সিং সক্ষমতা অবশ্যই সত্য বা মিথ্যা হতে হবে'
                    ],
                    'is_photo_taking_enabled' => [
                        'boolean' => 'ফটো তোলার সক্ষমতা অবশ্যই সত্য বা মিথ্যা হতে হবে'
                    ]
                ]
            ],
            'employeeDeparture' => [
                'departure_reason_id' => [
                    'exists' => 'নির্বাচিত চলে যাওয়ার কারণ বিদ্যমান নেই'
                ],
                'departure_description' => [
                    'string' => 'চলে যাওয়ার বিবরণ অবশ্যই স্ট্রিং হতে হবে'
                ],
                'departure_date' => [
                    'date' => 'চলে যাওয়ার তারিখ অবশ্যই একটি ভ্যালিড তারিখ হতে হবে'
                ]
            ],
        ],

        'jobPosition' => [
            'name' => [
                'required' => 'নাম প্রয়োজন',
                'unique' => 'নাম ইতিমধ্যেই বিদ্যমান'
            ]
        ],

        'department' => [
            'name' => [
                'required' => 'নাম প্রয়োজন',
                'unique' => 'নাম ইতিমধ্যেই বিদ্যমান'
            ],
            'parent_id' => [
                'exists' => 'নির্বাচিত প্যারেন্ট ডিপার্টমেন্টটি বিদ্যমান নেই'
            ]
        ],

        'leave' => [
            'leaveType' => [
                'name' => [
                    'required' => 'নাম প্রয়োজন',
                    'string' => 'নাম অবশ্যই স্ট্রিং হতে হবে',
                    'unique' => 'নাম ইতিমধ্যেই বিদ্যমান'
                ],
                'color' => [
                    'required' => 'রঙ প্রয়োজন',
                    'string' => 'রঙ অবশ্যই স্ট্রিং হতে হবে',
                    'unique' => 'রঙ ইতিমধ্যেই বিদ্যমান'
                ],
                'sequence' => [
                    'required' => 'সিকোয়েন্স প্রয়োজন',
                    'numeric' => 'সিকোয়েন্স অবশ্যই সংখ্যা হতে হবে',
                    'unique' => 'সিকোয়েন্স ইতিমধ্যেই বিদ্যমান'
                ],
                'leave_validation_type' => [
                    'required' => 'লিভ ভ্যালিডেশন টাইপ প্রয়োজন',
                    'in' => 'লিভ ভ্যালিডেশন টাইপ অবশ্যই এইচ.আর, ম্যানেজার, উভয়, ভ্যালিডেশন নেই - এর যেকোনো একটি হতে হবে'
                ],
                'request_unit' => [
                    'required' => 'রিকোয়েস্ট ইউনিট প্রয়োজন',
                    'in' => 'রিকোয়েস্ট ইউনিট অবশ্যই দিন, ঘণ্টা - এর যেকোনো একটি হতে হবে'
                ]
            ]
        ],

        'subscription' => [
            'subscription_plan_id' => [
                'required' => 'সাবস্ক্রিপশন প্ল্যান আইডি প্রয়োজন',
                'exists' => 'নির্বাচিত সাবস্ক্রিপশন প্ল্যানটি বিদ্যমান নেই'
            ]
        ],

        'commonComponents' => [
            'addressComponent' => [
                'address_line_one' => [
                    'string' => 'ঠিকানার প্রথম লাইন অবশ্যই স্ট্রিং হতে হবে'
                ],
                'address_line_two' => [
                    'string' => 'ঠিকানার দ্বিতীয় লাইন অবশ্যই স্ট্রিং হতে হবে'
                ],
                'floor' => [
                    'string' => 'তলার নম্বর অবশ্যই স্ট্রিং হতে হবে'
                ],
                'city' => [
                    'string' => 'শহরের নাম অবশ্যই স্ট্রিং হতে হবে'
                ],
                'state' => [
                    'string' => 'প্রদেশের নাম অবশ্যই স্ট্রিং হতে হবে'
                ],
                'zip_code' => [
                    'string' => 'জিপ কোড অবশ্যই স্ট্রিং হতে হবে'
                ]
            ]
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    'name_required' => 'নাম লিখুন',
    'name_not_in' => 'নাম বৈধ নয়',
    'mobile_number_required' => 'মোবাইল নম্বর লিখুন',
    'address_required' => 'ঠিকানা লিখুন',
    'mobile_number_unique' => 'মোবাইল নম্বর আগে থেকেই আছে',
    'month_required' => 'মাস প্রয়োজন',
    'year_required' => 'বছর প্রয়োজন',
    'date_required' => 'তারিখ দিন',
];

