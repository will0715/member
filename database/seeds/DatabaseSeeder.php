<?php

use Illuminate\Database\Seeder;
use Poyi\PGSchema\Facades\PGSchema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // init passport
        $this->call(PassportInitialSeeder::class);

        // init all permissions
        $this->call(PermissionDataSeeder::class);

        // init admin customer: poyi
        $this->call(AdminSchemaSeeder::class);
        $this->call(AdminCustomerTableSeeder::class);
    }
}
