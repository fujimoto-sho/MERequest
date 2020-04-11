<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('proposition_id')->unsigned()->comment('案件ID');
            $table->integer('user_id')->unsigned()->comment('ユーザーID');
            $table->timestamps();

            // UNIQUE制約
            $table->unique(['proposition_id', 'user_id']);

            // 外部キー制約
            $table->foreign('proposition_id')
                ->references('id')
                ->on('propositions');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
