<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsPersonInfoReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reserves', function (Blueprint $table) {
            //
            $table->unsignedInteger('gender')->after('name')->nullable()->comment('状況:1.男性、2.女性、3.その他');
            $table->string('age', 255)->after('gender')->nullable()->comment('年齢');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reserves', function (Blueprint $table) {
            //
            $table->dropColumn('gender');
            $table->dropColumn('age');
        });
    }
}
