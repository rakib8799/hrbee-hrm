<?php

namespace Database\Seeders;

use App\Models\Configuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('configurations')->truncate();

        $configurations = [
            [
                'option_name' => 'name',
                'option_value' => ''
            ],
            [
                'option_name' => 'workspace',
                'option_value' => ''
            ],
            [
                'option_name' => 'workspace_domain',
                'option_value' => ''
            ],
            [
                'option_name' => 'workspace_protocol',
                'option_value' => ''
            ],
            [
                'option_name' => 'other_language_id',
                'option_value' => '1'
            ],
            [
                'option_name' => 'timezone_id',
                'option_value' => '252'
            ],
            [
                'option_name' => 'date_format',
                'option_value' => 'YYYY-MM-DD'
            ],
            [
                'option_name' => 'industry',
                'option_value' => ''
            ],
            [
                'option_name' => 'is_roster',
                'option_value' => true
            ],
            [
                'option_name' => 'week_start_day',
                'option_value' => 'sunday'
            ],
            [
                'option_name' => 'weekends',
                'option_value' => ''
            ],
            [
                'option_name' => 'lunch_break_time',
                'option_value' => 60
            ],
            [
                'option_name' => 'organization_number',
                'option_value' => 0
            ],
            [
                'option_name' => 'logo',
                'option_value' => ''
            ],
            [
                'option_name' => 'favicon',
                'option_value' => ''
            ],
            [
                'option_name' => 'login_page_background_image',
                'option_value' => ''
            ],
            [
                'option_name' => 'website',
                'option_value' => ''
            ],
            [
                'option_name' => 'email',
                'option_value' => ''
            ],
            [
                'option_name' => 'admin_email',
                'option_value' => ''
            ],
            [
                'option_name' => 'telephone',
                'option_value' => ''
            ],
            [
                'option_name' => 'country_id',
                'option_value' => 18
            ],
            [
                'option_name' => 'address_line_one',
                'option_value' => ''
            ],
            [
                'option_name' => 'address_line_two',
                'option_value' => ''
            ],
            [
                'option_name' => 'floor',
                'option_value' => ''
            ],
            [
                'option_name' => 'city',
                'option_value' => ''
            ],
            [
                'option_name' => 'state',
                'option_value' => ''
            ],
            [
                'option_name' => 'zip_code',
                'option_value' => ''
            ],
            [
                'option_name' => 'email_provider_id',
                'option_value' => ''
            ],
            [
                'option_name' => 'support_email',
                'option_value' => ''
            ],
            [
                'option_name' => 'support_telephone',
                'option_value' => ''
            ]
        ];

        Configuration::insert($configurations);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
