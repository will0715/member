<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_groups', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('name');
            $table->string('code')->unique();

            $table->boolean('limit_quantity')->default(false);
            $table->integer('total_quantity')->default(0);
            $table->integer('current_quantity')->default(0);

            $table->boolean('limit_branch')->default(false);
            $table->boolean('can_get_multi')->default(false);

            $table->timestamp('activated_at');
            $table->timestamp('expired_at');

            // earn rule type amount/item_count
            $table->jsonb('trigger_condition');
            $table->jsonb('content');

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('coupon_group_branch', function (Blueprint $table) {
            $table->uuid('coupon_group_id');
            $table->uuid('branch_id');

            $table->foreign('coupon_group_id')
                ->references('id')
                ->on('coupon_groups')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');

            $table->primary(['coupon_group_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_group_branch');
        Schema::dropIfExists('coupon_groups');
    }
}
