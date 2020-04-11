<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->comment('応募ID');
            $table->integer('user_id')->unsigned()->comment('ユーザーID');
            $table->string('message')->comment('メッセージ');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー制約
            $table->foreign('application_id')
                ->references('id')
                ->on('applications');
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
        Schema::dropIfExists('direct_messages');
    }
}
