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

class AdminCustomerTableSeeder extends Seeder
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

        $migrate = Artisan::call('pgschema:migrate', ["--schema" => $schema , "--force" => "true"]);

        Customer::create([
            'db_schema' => $schema,
            'name' => $name
        ]);
        PGSchema::schema($schema, 'pgsql');

        //Add HQ branch
        $branch = Branch::create(array('code' => 'HQ', 'name' => 'HQ', 'store_name' => 'HQ'));

        //Add user
        $user = User::create(array('name' => $name, 'email' => $account, 'password' => Hash::make($password)));
        $user = User::where('name', $name)->first();

        //Add admin role
        $admin = Role::create(array('name' => 'admin', 'guard_name' => 'api' ));
        $user->assignRole('admin');

        //Add superadmin admin permission
        $superAdmin = Permission::where('name', 'super-admin')->get();
        $user->givePermissionTo($superAdmin);

        //Add basic rank
        $rank = Rank::create([
            'rank' => 1,
            'name' => '一般會員'
        ]);

        //Add basic chop expired setting
        $chopExpiredSetting = ChopExpiredSetting::create([
            'expired_date' => 365,
        ]);

    }
}
