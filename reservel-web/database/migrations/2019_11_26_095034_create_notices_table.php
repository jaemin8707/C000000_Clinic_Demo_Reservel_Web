<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sort')->comment('表示順');
            $table->text('notice_text', 4000)->nullable()->comment('お知らせ内容');
            $table->dateTime('start_time')->nullable()->comment('掲示開始日');
            $table->dateTime('end_time')->nullable()->comment('掲示終了日');
            $table->boolean('post_flg')->nullable()->default(true)->comment('表示フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
