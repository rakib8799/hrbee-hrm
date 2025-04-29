<?php

namespace Database\Seeders;

use App\Constants\Constants;
use App\Models\HR\Leave\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'id' => 1,
                'name' => 'Family Leave',
                'color' => '#ff9800',
                'sequence' => 1,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ],
            [
                'id' => 2,
                'name' => 'Maternity Leave',
                'color' => '#ffb6c1',
                'sequence' => 2,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ],
            [
                'id' => 3,
                'name' => 'Paternity Leave',
                'color' => '#ffeb3b',
                'sequence' => 3,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ],
            [
                'id' => 4,
                'name' => 'Annual Leave',
                'color' => '#42a5f5',
                'sequence' => 4,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ],
            [
                'id' => 5,
                'name' => 'Sick Leave',
                'color' => '#ff6b6b',
                'sequence' => 5,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ],
            [
                'id' => 6,
                'name' => 'Casual Leave',
                'color' => '#4caf50',
                'sequence' => 6,
                'leave_validation_type' => Constants::BOTH,
                'request_unit' => Constants::DAY
            ]
        ];

        LeaveType::insert($leaveTypes);
    }
}
