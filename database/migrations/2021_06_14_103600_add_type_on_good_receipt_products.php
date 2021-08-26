<?php

use Doctrine\DBAL\Schema\Schema as SchemaSchema;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeOnGoodReceiptProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_receipt_products', function(Blueprint $table){
            $table->dropColumn('references');
            $table->unsignedBigInteger('goods_receipt_id');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('uom_id');
            $table->string('type')->nullable();      
            
            $table->foreign('goods_receipt_id')->references('id')->on('goods_receipts')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('goods_receipts', function(Blueprint $table){
            $table->string('key_number')->nullable(); 
            $table->string('good_receipt_no')->nullable()->change();
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_receipt_products',function(Blueprint $table){
            $table->dropForeign(['goods_receipt_id']);
            
            $table->dropColumn('goods_receipt_id');
            $table->dropColumn('reference_id');
            $table->dropColumn('uom_id');
            $table->dropColumn('type');            
            $table->string('references')->nullable();            
        });

        Schema::table('goods_receipts', function(Blueprint $table){
            $table->dropColumn('key_number');
        });
    }
}
