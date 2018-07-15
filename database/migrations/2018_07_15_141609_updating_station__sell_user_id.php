<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatingStationSellUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stationSell', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 18, 2);
            $table->decimal('quantity', 8, 1);
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('type');   
            $table->integer('station_id')->unsigned();
            $table->foreign('station_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('stationSell', function (Blueprint $table) {
            //
        });
    }
}
