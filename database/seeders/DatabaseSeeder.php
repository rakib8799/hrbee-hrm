<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            CountrySeeder::class,
            ConfigurationSeeder::class,
            TimezoneSeeder::class,
            LanguageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            DepartureReasonSeeder::class,
            LeaveTypeSeeder::class
        ]);
    }
}


