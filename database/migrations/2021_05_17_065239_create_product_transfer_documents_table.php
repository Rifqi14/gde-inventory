<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransferDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transfer_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_transfer_id');
            $table->text('document_name');
            $table->text('file');
            $table->string('type');
            $table->timestamps();

            $table->foreign('product_transfer_id')->references('id')->on('product_transfers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_transfer_documents');
    }
}