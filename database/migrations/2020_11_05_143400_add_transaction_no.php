<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prepaid_card_records', function (Blueprint $table) {
            $table->string('transaction_no')->nullable();
            $table->index('transaction_no');
        });
        
        Schema::table('chop_records', function (Blueprint $table) {
            $table->index('transaction_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chop_records', function (Blueprint $table) {
            $table->dropColumn('transaction_no');
            $table->uuid('transaction_id')->nullable();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');
        });
    }
}
