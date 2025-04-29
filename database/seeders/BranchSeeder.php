<?php

namespace Database\Seeders;

use App\Models\Branch\Branch;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('branches')->truncate();

        $branches = [
            [
                'id' => 1,
                'name' => 'Mirpur',
                'created_by' => 1,
                'updated_by' => 1
            ],
            [
                'id' => 2,
                'name' => 'Banani',
                'created_by' => 1,
                'updated_by' => 1
            ]
        ];
        
        Branch::insert($branches);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
