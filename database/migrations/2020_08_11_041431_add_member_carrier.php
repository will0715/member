<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberCarrier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('card_carrier_no')->nullable();
            $table->string('invoice_carrier_no')->nullable();

            $table->index('card_carrier_no');
            $table->index('invoice_carrier_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('card_carrier_no');
            $table->dropColumn('invoice_carrier_no');
        });
    }
}
