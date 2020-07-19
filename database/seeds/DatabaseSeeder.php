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
        $this->call(AdminSchemaSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(PassportInitialSeeder::class);
    }
}
