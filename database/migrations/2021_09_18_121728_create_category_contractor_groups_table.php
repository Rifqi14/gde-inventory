<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryContractorGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_contractor_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_contractor_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('category_contractor_id')->references('id')->on('category_contractors')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('category_contractor_groups');
    }
}
