<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductBorrowingSerials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_borrowing_serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('borrowing_detail_id');
            $table->unsignedBigInteger('serial_id');
            $table->timestamps();

            $table->foreign('borrowing_detail_id')->references('id')->on('product_borrowing_details')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('serial_id')->references('id')->on('product_serials')->onUpdate('cascade')->onDelete('cascade');
        });
        
        Schema::table('product_serials', function(Blueprint $table){            
            $table->string('movement')->nullable();                        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_borrowing_serials');
        Schema::table('product_serials', function(Blueprint $table){        
            $table->dropColumn('movement');            
        });
    }
}
