<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExcludeDestinationToEarnChopRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earn_chop_rules', function (Blueprint $table) {
            $table->text('exclude_destination')->nullable();
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
