<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closed', function (Blueprint $table) {
						$table->bigIncrements('id');
						$table->date('closed_day')->comment('休診日');
						$table->unsignedTinyInteger('closed_type')->comment('休診区分:1.午前、2.午後、3.全日');
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
        Schema::dropIfExists('closed');
    }
}
