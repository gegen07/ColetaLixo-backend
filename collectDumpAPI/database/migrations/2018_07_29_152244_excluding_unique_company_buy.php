<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExcludingUniqueCompanyBuy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companyBuys', function (Blueprint $table) {
            $table->dropForeign('companybuys_company_id_foreign');
            $table->dropUnique('companybuys_company_id_unique');
            $table->foreign('company_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companyBuys', function (Blueprint $table) {
            //
        });
    }
}
