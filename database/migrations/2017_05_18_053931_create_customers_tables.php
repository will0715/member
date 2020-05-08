<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Poyi\PGSchema\Facades\PGSchema;

class CreateCustomersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {

        // Create table for storing customers
        if (!PGSchema::tableExists('customers', 'public')) {
            Schema::create('public.customers', function (Blueprint $table) {
                $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
                $table->string('db_schema')->unique();
                $table->string('name')->unique();
                $table->timestamps();
                $table->timestamp('deleted_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        //
    }
}
