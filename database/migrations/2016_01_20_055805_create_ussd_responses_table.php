<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUssdResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ussd_responses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->integer('menu_id')->unsigned();
            $table->integer('menu_item_id')->unsigned();
            $table->string('response', 45);
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
        Schema::drop('ussd_responses');
    }
}
