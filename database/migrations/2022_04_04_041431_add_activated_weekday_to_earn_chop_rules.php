<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActivatedWeekdayToEarnChopRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earn_chop_rules', function (Blueprint $table) {
            $table->string('activated_weekday')->nullable();
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
            $table->dropColumn('exclude_destination');
        });
    }
}
