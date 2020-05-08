<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEarnChopRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earn_chop_rules', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->uuid('rank_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('payment_type')->default('All');

            // earn rule type amount/item_count
            $table->string('type')->default('AMOUNT');

            $table->float('rule_unit', 8, 2)->default(0);
            $table->float('rule_chops', 8, 2)->default(0);
            $table->text('exclude_product')->nullable();
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
        Schema::dropIfExists('earn_chop_rules');
    }
}
