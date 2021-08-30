<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBorrowingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_borrowing_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_borrowing_id');
            $table->text('file');
            $table->string('type');
            $table->timestamps();

            $table->foreign('product_borrowing_id')->references('id')->on('product_borrowings')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_borrowing_documents');
    }
}