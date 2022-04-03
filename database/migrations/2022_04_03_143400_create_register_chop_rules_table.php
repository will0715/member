<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterChopRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_chop_rules', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            
            $table->boolean('is_active')->default(false);
            $table->string('name');
            $table->string('description')->nullable();
            $table->float('rule_chops', 8, 2)->default(0);

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
}
