<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVehicleTypeAndPoliceNumberToProductTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_transfers', function (Blueprint $table) {
            $table->string('vehicle_type')->nullable();
            $table->string('police_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_transfers', function (Blueprint $table) {
            $table->dropColumn(['vehicle_type', 'police_number']);
        });
    }
}