<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransmittalOrganizationCodeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transmittal_organization_code_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transmittal_organization_code_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('transmittal_organization_code_id')->references('id')->on('transmittal_organization_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transmittal_organization_code_groups');
    }
}
