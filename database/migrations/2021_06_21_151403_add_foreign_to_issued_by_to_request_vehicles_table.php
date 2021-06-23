<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignToIssuedByToRequestVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_vehicles', function (Blueprint $table) {
            $table->foreign('issued_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('revise_status')->nullable()->default('NO');
            $table->string('revise_number')->nullable()->default(0);
            $table->text('revise_reason')->nullable();
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
            $table->dropForeign(['issued_by']);
            $table->dropColumn(['revise_status', 'revise_number', 'revise_reason']);
        });
    }
}