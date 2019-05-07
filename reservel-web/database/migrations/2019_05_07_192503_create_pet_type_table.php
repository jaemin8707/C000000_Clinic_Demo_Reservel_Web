<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePetTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pet_type', function (Blueprint $table) {
            $table->bigIncrements('id', true);
            $table->bigInteger('reserve_id')->comment('予約ID');
            $table->unsignedTinyInteger('pet_type')->comment('ペット種類:1.犬、2.猫、99.その他');
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
        Schema::dropIfExists('pet_type');
    }
}
