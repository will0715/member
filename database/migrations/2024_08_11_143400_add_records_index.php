<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecordsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('transaction_time');
        });

        Schema::table('chop_records', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('created_at');
        });

        Schema::table('prepaid_card_records', function (Blueprint $table) {
            $table->index('member_id');
            $table->index('created_at');
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
            $table->index('member_id');
            $table->dropIndex('transaction_time');
        });

        Schema::table('chop_records', function (Blueprint $table) {
            $table->index('member_id');
            $table->dropIndex('created_at');
        });

        Schema::table('prepaid_card_records', function (Blueprint $table) {
            $table->index('member_id');
            $table->dropIndex('created_at');
        });
    }
}