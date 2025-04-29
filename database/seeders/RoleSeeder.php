<?php

namespace Database\Seeders;

use App\Constants\Constants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();

        $roles = [
            [
                'id' => 1,
                'name' => Constants::ROLE_SUPER_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => Constants::ROLE_ADMIN,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => Constants::ROLE_EMPLOYEE,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => Constants::ROLE_HR,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ],
            [
                'id' => 5,
                'name' => Constants::ROLE_LINE_MANAGER,
                'guard_name' => 'web',
                'is_editable' => true,
                'is_deletable' => false,
                'is_available' => true,
                'is_active' => true,
            ]
        ];
        
        Role::insert($roles);

        $superAdmin = Role::find(1);
        $permissions = Permission::all();
        $superAdmin->syncPermissions($permissions);

        $hr = Role::find(4);
        $permissions = Permission::whereNotIn('id', [41, 42, 43, 44, 45])->get();
        $hr->syncPermissions($permissions);

        $employee = Role::find(3);
        $employeePermissions = Permission::whereIn('id', [41, 42, 43, 44, 45, 66, 67, 70])->get();
        $employee->syncPermissions($employeePermissions);

        $lineManager = Role::find(5);
        $lineManagerPermissions = Permission::whereIn('id', [17, 18, 46, 47, 48, 67, 68, 69])->get();
        $lineManager->syncPermissions($lineManagerPermissions);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
