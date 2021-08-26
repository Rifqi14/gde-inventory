<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductConsumableDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_consumable_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_consumable_id')->nullable;
            $table->string('document_name');
            $table->text('file');
            $table->string('type');
            $table->timestamps();

            $table->foreign('product_consumable_id')->references('id')->on('product_consumables')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_consumable_documents');
    }
}