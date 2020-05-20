<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberRegisterBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_register_branch', function (Blueprint $table) {
            $table->uuid('member_id')->unique();
            $table->uuid('branch_id');
            $table->timestamp('register_at');
            $table->timestamps();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');

            $table->foreign('branch_id')
                ->references('id')
                ->on('branches')
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
        Schema::dropIfExists('member_register_branch');
    }
}
