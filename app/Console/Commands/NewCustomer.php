<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use PGSchema;
use Artisan;
use DB;

class NewCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->option('name');
        $account = $this->option('account');
        $password = $this->option('password');
        $schema = 'db_'.$name;
        
        //Add customer data in public
        Customer::create([
            'db_name' => $schema,
            'name' => $name,
        ]);
        
        $createSchema = DB::connection()->statement('create schema ' . $schema . ' IF NOT EXIST');
        PGSchema::schema($schema, 'pgsql');

        DB::select('CREATE SEQUENCE "migrations_id_seq";');
        DB::select('CREATE TABLE "migrations" ("id" int4 not null PRIMARY KEY, "migration" varchar(255) not null, "batch" int4 not null)');
        DB::select('ALTER SEQUENCE "migrations_id_seq" START 1 MINVALUE 1 OWNED BY "migrations"."id";');
        DB::select('ALTER TABLE "migrations" ALTER "id" set DEFAULT nextval(\''. $schema .'.migrations_id_seq\'::regclass);');
        
        $migrate = Artisan::call('pgschema:migrate', ["--schema" => $schema , "--force" => "true"]);

        //Add user
        $user = User::create(array('name' => $name, 'email' => $account, 'password' =>  bcrypt($userPassword)));
        $user = User::where('name', $userAccount)->first();

        //Add admin role
        $admin = Role::create(array('name' => 'admin', 'guard_name' => 'admin' ));
        $user->assignRole('admin');
    }
}
