<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('phone');

            $table->timestamp('activated_at');
            $table->timestamp('expired_at');

            // earn rule type amount/item_count
            $table->jsonb('trigger_condition');
            $table->jsonb('discount');

            $table->timestamp('write_off_at');
            $table->string('write_off_branch');

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('coupon_branch', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('branch_id');

            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');

            $table->primary(['coupon_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_branch');
        Schema::dropIfExists('coupons');
    }
}
