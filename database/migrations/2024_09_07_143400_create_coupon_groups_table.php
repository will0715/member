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
            $table->string('prefix_code')->unique();

            $table->boolean('limit_branch')->default(false);
            $table->boolean('limit_rank')->default(false);

            // 計算時間方式，固定時間、領取後有效期
            $table->string('calculate_time_unit')->comment('FIXED, CLAIM');

            // 固定開始結束時間
            $table->dateTime('fixed_start_time')->nullable();
            $table->dateTime('fixed_end_time')->nullable();

            // 領取後有效期
            $table->integer('valid_days_after_claim')->nullable();

            $table->boolean('can_trigger_others')->default(false);

            $table->jsonb('trigger_condition');
            $table->jsonb('content');

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('coupon_group_rank', function (Blueprint $table) {
            $table->uuid('coupon_group_id');
            $table->uuid('rank_id');

            $table->foreign('coupon_group_id')
                ->references('id')
                ->on('coupon_groups')
                ->onDelete('cascade');

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks')
                ->onDelete('cascade');

            $table->primary(['coupon_group_id', 'rank_id']);
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
        Schema::dropIfExists('coupon_group_rank');
        Schema::dropIfExists('coupon_groups');
    }
}
