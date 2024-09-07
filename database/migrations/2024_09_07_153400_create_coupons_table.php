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
            $table->uuid('coupon_group_id');
            $table->uuid('member_id')->nullable();
            $table->string('code')->unique();
            $table->string('status')->default('AVAILABLE')->comment('AVAILABLE, USED, DISABLED');
            $table->dateTime('claimed_at')->nullable();
            $table->dateTime('used_at')->nullable();
            $table->dateTime('effective_start_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->jsonb('usage_data')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('coupon_group_id')
                ->references('id')
                ->on('coupon_groups')
                ->onDelete('cascade');

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
