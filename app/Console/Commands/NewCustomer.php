<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\Rank;
use App\Models\ChopExpiredSetting;
use App\Services\CustomerService;
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
    protected $signature = 'customer:add {name : customer name} 
                            {--account= : admin account} 
                            {--password= : admin password} 
                            {--permissions= : admin permission}
                            {--expired_at= : customer expired at}';

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
        $this->customerService = app(CustomerService::class);
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
        $expiredAt = $this->option('expired_at');
        $permissions = explode(',', $this->option('permissions'));
        $schema = 'db_'.$name;
        
        // $createSchema = DB::connection()->statement('create schema IF NOT EXISTS ' . $schema);
        // PGSchema::schema($schema, 'pgsql');

        DB::beginTransaction();
        try {
            $this->customerService->newCustomer([
                'name' => $name,
                'expired_at' => $expiredAt,
            ]);

            $this->customerService->initCustomer([
                'name' => $name,
                'expired_at' => $expiredAt,
                'permissions' => $permissions
            ]);
            DB::commit();

            $this->info('Create Customer Done');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Create Customer Failed');
        }
    }
}
