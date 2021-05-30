<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_coupons', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('phone')->nullable();
            $table->string('product_name');
            $table->string('product_no');
            $table->string('code')->unique();
            $table->integer('quantity');
            $table->integer('consumed_quantity');
            $table->boolean('limit_branch')->default(false);
            $table->float('price');
            $table->text('condiments')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('last_consumed_at')->nullable();

            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pickup_coupons');
    }
}
