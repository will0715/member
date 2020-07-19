<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\ChopExpiredSetting;
use Poyi\PGSchema\Facades\PGSchema;
use Artisan;
use DB;
use Hash;

class NewCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:add {name : customer name} {--account= : admin account} {--password= : admin password}';

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
        $name = $this->argument('name');
        $account = $this->option('account');
        $password = $this->option('password');
        $schema = 'db_'.$name;
        
        $createSchema = DB::connection()->statement('create schema IF NOT EXISTS ' . $schema);
        PGSchema::schema($schema, 'pgsql');

        DB::beginTransaction();
        try {
        
            //Add customer data in public
            Customer::create([
                'db_schema' => $schema,
                'name' => $name,
            ]);
            
            $migrate = Artisan::call('pgschema:migrate', ["--schema" => $schema , "--force" => "true"]);
    
            //Add HQ branch
            $branch = Branch::create(array('code' => 'HQ', 'name' => 'HQ', 'store_name' => 'HQ'));
    
            //Add user
            $user = User::create(array('name' => $name, 'email' => $account, 'password' => Hash::make($password)));
            $user = User::where('name', $name)->first();
    
            //Add admin role
            $admin = Role::create(array('name' => 'admin', 'guard_name' => 'api' ));
    
            $user->assignRole('admin');
    
            //Add basic rank
            $rank = Rank::create([
                'rank' => 1,
                'name' => '一般會員'
            ]);
    
            //Add basic chop expired setting
            $chopExpiredSetting = ChopExpiredSetting::create([
                'expired_date' => 365,
            ]);
            DB::commit();

            $this->info('Create Customer Done');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Create Customer Failed');
            throw $e;
        }
    }
}
