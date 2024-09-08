<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRankUpgradeIssueCoupon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rank_upgrade_settings', function (Blueprint $table) {
            $table->boolean('issue_coupon')->default(false)->comment('是否贈送優惠券');
            $table->uuid('issue_coupon_group_id')->nullable()->comment('贈送的優惠券群組ID');
            $table->integer('issue_coupon_quantity')->default(0)->comment('贈送的優惠券數量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rank_upgrade_settings', function (Blueprint $table) {
            $table->dropColumn('issue_coupon');
            $table->dropColumn('issue_coupon_group_id');
            $table->dropColumn('issue_coupon_quantity');
        });
    }
}
