<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupCouponBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_coupon_branch', function (Blueprint $table) {
            $table->uuid('pickup_coupon_id');
            $table->uuid('branch_id');

            $table->foreign('pickup_coupon_id')
                ->references('id')
                ->on('pickup_coupons')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');

            $table->primary(['pickup_coupon_id', 'branch_id'], 'pickup_coupon_branch_pickup_coupon_id_branch_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup_coupon_branch');
    }
}
