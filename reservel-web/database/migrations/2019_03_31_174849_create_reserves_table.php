<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserves', function (Blueprint $table) {
            $table->bigIncrements('id', true)->comment('予約ID');
            $table->unsignedTinyInteger('place')->comment('受付場所:1.院内、2.院外');
            $table->unsignedSmallInteger('reception_no')->comment('受付番号');
            $table->unsignedTinyInteger('care_type')->comment('受付番号 1.初診、2.再診');
            $table->tinyInteger('status')->comment('状況:10.待ち、20.呼び出し済、30.診察中、40.完了、-1.キャンセル');
            $table->string('name', 50)->nullable()->comment('名前:院外受付のみ');
            $table->string('medical_card_no', 100)->nullable()->comment('診察券番号:院外受付のみ');
            $table->string('pet_type', 255)->nullable()->comment('ペット種類:複数入力可、院外受付のみ');
            $table->string('pet_name', 255)->nullable()->comment('ペット名:複数入力可、院外受付のみ');
            $table->string('tel', 25)->nullable()->comment('電話番号:院外受付のみ');
            $table->string('email', 255)->nullable()->comment('メールアドレス:院外受付のみ');
            $table->text('conditions', 4000)->nullable()->comment('症状:院外受付のみ');
            $table->string('cancel_token', 100)->nullable()->comment('受付キャンセルトークン:院外受付のみ');
            $table->dateTime('call_time')->nullable()->comment('呼出時刻');
            $table->boolean('send_remind')->nullable()->default(false)->comment('リマインドメール送信フラグ');
            $table->timestamps();
            $table->integer('created_id')->nullable()->comment('登録者');
            $table->integer('updated_id')->nullable()->comment('修正者');
        });
        DB::statement("ALTER TABLE reserves COMMENT '予約'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserves');
    }
}
