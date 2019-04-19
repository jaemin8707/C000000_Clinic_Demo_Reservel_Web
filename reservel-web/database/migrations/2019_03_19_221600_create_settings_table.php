<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id', true)->comment('設定ID');
            $table->string('code', 500)->unique()->comment('設定コード');
            $table->text('value', 4000)->nullable()->comment('設定値');
            $table->text('description', 4000)->nullable()->comment('備考');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE settings COMMENT '設定'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
