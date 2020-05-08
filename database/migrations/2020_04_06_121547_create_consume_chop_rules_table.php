<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumeChopRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consume_chop_rules', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->uuid('rank_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('payment_type')->default('All');
            // earn rule type amount/item
            $table->string('type')->default('AMOUNT');
            $table->float('chops_per_unit', 8, 2)->default(0);
            $table->float('unit_per_amount', 8, 2)->default(0);
            $table->float('consume_max_percentage', 8, 2)->default(100);
            $table->boolean('earn_chops_after_consume')->default(false);
            $table->timestamps();
            $table->timestamp('activated_at');
            $table->timestamp('expired_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consume_chop_rules');
    }
}
