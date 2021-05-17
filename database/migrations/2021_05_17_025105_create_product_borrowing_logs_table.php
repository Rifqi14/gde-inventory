<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBorrowingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_borrowing_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_borrowing_id');
            $table->unsignedBigInteger('issued_by');
            $table->string('log_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_borrowing_logs');
    }
}