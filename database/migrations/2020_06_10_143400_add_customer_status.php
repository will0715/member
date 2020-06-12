<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Poyi\PGSchema\Facades\PGSchema;

class AddCustomerStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!PGSchema::columnExists('status', 'customers', 'public')) {
            Schema::table('public.customers', function (Blueprint $table) {
                $table->integer('status')->default(1);
            });
        }

        if (!PGSchema::columnExists('expired_at', 'customers', 'public')) {
            Schema::table('public.customers', function (Blueprint $table) {
                $table->timestamp('expired_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
