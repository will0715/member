<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveEarnChopsAfterConsumeToEarnChopRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earn_chop_rules', function (Blueprint $table) {
            $table->boolean('earn_chops_after_consume')->default(false);
        });

        Schema::table('consume_chop_rules', function (Blueprint $table) {
            $table->dropColumn('earn_chops_after_consume');
            $table->text('exclude_product')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('earn_chop_rules', function (Blueprint $table) {
            $table->dropColumn('earn_chops_after_consume');
        });

        Schema::table('consume_chop_rules', function (Blueprint $table) {
            $table->boolean('earn_chops_after_consume')->default(false);
            $table->dropColumn('exclude_product');
        });
    }
}
