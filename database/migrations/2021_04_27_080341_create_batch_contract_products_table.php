<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBatchContractProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batch_contract_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_product_id');
            $table->unsignedBigInteger('batch_contract_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('contract_product_id')->references('id')->on('contract_products')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('batch_contract_id')->references('id')->on('batch_contracts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('batch_contract_products');
    }
}