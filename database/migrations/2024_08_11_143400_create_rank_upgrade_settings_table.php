<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankUpgradeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rank_upgrade_settings', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->uuid('rank_id');

            $table->boolean('is_active')->default(false);
            $table->string('calculate_standard')->comment('amount, times, chop');
            $table->decimal('calculate_standard_value', 8 ,2);
            $table->string('calculate_time_unit')->comment('day, month, year');
            $table->integer('calculate_time_value');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rank_upgrade_settings');
    }
}
