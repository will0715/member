<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupCouponConsumedHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_coupon_consumed_histories', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->uuid('pickup_coupon_id');
            $table->integer('consumed_quantity');
            $table->string('consumed_branch');
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->timestamp('consumed_at');

            $table->foreign('pickup_coupon_id')
                ->references('id')
                ->on('pickup_coupons')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup_coupon_consumed_histories');
    }
}
