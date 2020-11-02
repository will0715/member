<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Poyi\PGSchema\Facades\PGSchema;
use Artisan;
use DB;
use Hash;

class ProcessMemberPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member:process_password
                            {--schema= : schema} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process member password hash';

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
        $name = $this->option('schema');
        $schema = 'db_'.$name;

        DB::beginTransaction();
        try {
            PGSchema::schema($schema, 'pgsql');
            $members = Member::where('password', 'not like', '$2y%')->get();

            foreach ($members as $member) {
                $member->password = Hash::make($member->password);
                $member->save();
            }

            DB::commit();

            $this->info('Process Member Password Done');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Process Member Password Failed');
        }
    }
}
