<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use Poyi\PGSchema\Facades\PGSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSchemaSeeder extends Seeder
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
        // Seed the database
        $createSchema = DB::connection()->statement('create schema IF NOT EXISTS ' . $schema);
    }
}
