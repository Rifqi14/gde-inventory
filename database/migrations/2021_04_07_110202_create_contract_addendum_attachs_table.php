<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractAddendumAttachsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_addendum_attachs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('addendum_id')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();
            $table->foreign('addendum_id')->references('id')->on('contract_addendums')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_addendum_attachs', function (Blueprint $table) {
            $table->dropForeign(['addendum_id']);
        });
        Schema::dropIfExists('contract_addendum_attachs');
    }
}
