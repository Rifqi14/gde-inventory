<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTransferLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transfer_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_transfer_id');
            $table->unsignedBigInteger('issued_by');
            $table->text('log_description')->nullable();
            $table->timestamps();

            $table->foreign('product_transfer_id')->references('id')->on('product_transfers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_transfer_logs');
    }
}