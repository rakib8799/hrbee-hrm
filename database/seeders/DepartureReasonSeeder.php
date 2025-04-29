<?php

namespace Database\Seeders;

use App\Models\HR\DepartureReason;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartureReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hr_departure_reasons')->truncate();

        $departureReasons = [
            [
                'id' => 1,
                'name' => 'Fired',
                'slug' => 'fired'
            ],
            [
                'id' => 2,
                'name' => 'Resigned',
                'slug' => 'resigned'
            ],
            [
                'id' => 3,
                'name' => 'Retired',
                'slug' => 'retired'
            ]
        ];

        DepartureReason::insert($departureReasons);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
