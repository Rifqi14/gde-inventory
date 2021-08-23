<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyIdToBusinessTripOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trip_others', function (Blueprint $table) {
            $table->unsignedBigInteger('currency_id')->nullable();

            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_trip_others', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });
    }
}
