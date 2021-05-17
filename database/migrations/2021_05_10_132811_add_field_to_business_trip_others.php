<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToBusinessTripOthers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trip_others', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->integer('price')->nullable();
            $table->integer('qty')->nullable();
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
            $table->dropColumn('description');
            $table->dropColumn('price');
            $table->dropColumn('qty');
        });
    }
}
