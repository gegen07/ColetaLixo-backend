<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyBuyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companyBuys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stationSell_id')->unique()->unsigned();
            $table->integer('company_id')->unique()->unsigned(); // TODO: Remember to add foreign key contraint
            $table->foreign('stationSell_id')->references('id')->on('stationSell');
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
        Schema::dropIfExists('companyBuys');
    }
}
