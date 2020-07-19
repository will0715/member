<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionDetailColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('destination')->nullable();
            $table->float('discount')->default(0);
            $table->integer('chops')->default(0);
            $table->integer('consume_chops')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('destination');
            $table->dropColumn('discount');
            $table->dropColumn('chops');
            $table->dropColumn('consume_chops');
        });
    }
}
