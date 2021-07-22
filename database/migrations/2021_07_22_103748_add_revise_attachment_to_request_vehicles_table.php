<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviseAttachmentToRequestVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_vehicles', function (Blueprint $table) {
            $table->string('revise_attachment_name')->nullable();
            $table->string('revise_attachment')->nullable();
        });

        Schema::table('log_revises', function (Blueprint $table) {
            $table->string('attachment_name')->nullable();
            $table->string('revise_attachment')->nullable();
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
            $table->dropColumn(['revise_attachment', 'revise_attachment_name']);
        });

        Schema::table('log_revises', function (Blueprint $table) {
            $table->dropColumn(['revise_attachment', 'attachment_name']);
        });
    }
}
