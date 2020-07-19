<?php

use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\Permission;
use App\Models\ChopExpiredSetting;
use Poyi\PGSchema\Facades\PGSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GiveSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schema = 'db_poyi';
        $name = 'poyi';
        $account = 'w67890w67890@gmail.com';
        $password = '123123';

        PGSchema::schema($schema, 'pgsql');

        //Add user
        $user = User::where('name', $name)->first();

        //Add superadmin admin permission
        $superAdmin = Permission::where('name', 'super-admin')->get();
        $user->givePermissionTo($superAdmin);
    }
}
