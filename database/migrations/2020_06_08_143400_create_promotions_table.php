<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('name');

            // earn rule type amount/item_count

            $table->jsonb('discount')->nullable();

            $table->timestamp('activated_at');
            $table->timestamp('expired_at');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });

        Schema::create('promotion_rank', function (Blueprint $table) {
            $table->uuid('promotion_id');
            $table->uuid('rank_id');

            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->onDelete('cascade');

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks')
                ->onDelete('cascade');

            $table->primary(['promotion_id', 'rank_id']);
        });

        Schema::create('promotion_branch', function (Blueprint $table) {
            $table->uuid('promotion_id');
            $table->uuid('branch_id');

            $table->foreign('promotion_id')
                ->references('id')
                ->on('promotions')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
                ->onDelete('cascade');

            $table->primary(['promotion_id', 'branch_id']);
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
        Schema::dropIfExists('promotion_rank');
        Schema::dropIfExists('promotions');
    }
}
