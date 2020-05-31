<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionItemCondimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_item_condiments', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->uuid('transaction_item_id');
            $table->string('no');
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('subtotal', 8, 2);
            $table->text('remark')->nullable();
            $table->timestamps();

            $table->foreign('transaction_item_id')
                ->references('id')
                ->on('transaction_items')
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
        Schema::dropIfExists('transaction_item_condiments');
    }
}
