<?php

use Illuminate\Database\Seeder;
use App\Constants\PermissionConstant;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PermissionDataSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (PermissionConstant::ALL_PERMISSIONS as $group => $permissions) {
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'api',
                    'group' => $group
                ]);
            }
        }

    }
}
