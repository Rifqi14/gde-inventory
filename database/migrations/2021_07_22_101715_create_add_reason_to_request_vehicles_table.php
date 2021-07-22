<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddReasonToRequestVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_vehicles', function (Blueprint $table) {
            $table->text('reason')->nullable();
            $table->text('attachment_name')->nullable();
            $table->text('reason_attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_vehicles', function (Blueprint $table) {
            $table->dropColumn(['reason', 'attachment_name', 'reason_attachment']);
        });
    }
}
