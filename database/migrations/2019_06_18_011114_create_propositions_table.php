<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propositions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('ユーザーID');
            $table->string('title')->comment('タイトル');
            $table->integer('type')->comment('案件タイプ');
            $table->integer('number_min')->comment('金額/%（最小）');
            $table->integer('number_max')->comment('金額/%（最大）');
            $table->integer('recruiting_count')->comment('募集人数');
            $table->text('content')->comment('内容');
            $table->integer('status')->comment('募集状況');
            $table->timestamps();
            $table->softDeletes();

            // 外部キー制約
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
        Schema::dropIfExists('propositions');
    }
}
