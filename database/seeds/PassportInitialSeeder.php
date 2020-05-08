<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\ChopExpiredSetting;
use Poyi\PGSchema\Facades\PGSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PassportInitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // passport init
        $migrate = Artisan::call('php artisan passport:install');
    }
}
