<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->uuid('id')->default(DB::raw('public.uuid_generate_v4()'))->primary();
            $table->string('phone')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('password');
            $table->enum('gender', ['male', 'female', 'others', 'unknown'])->default('unknown');
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('remark')->nullable();
            $table->uuid('rank_id');
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('rank_id')
                ->references('id')
                ->on('ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
