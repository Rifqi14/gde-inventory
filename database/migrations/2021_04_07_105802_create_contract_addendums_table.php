<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractAddendumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_addendums', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->double('times')->default(0);
            $table->text('currency')->nullable();
            $table->double('value')->default(0);
            $table->date('expiration_moved')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_addendums', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
        Schema::dropIfExists('contract_addendums');
    }
}
