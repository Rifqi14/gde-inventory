<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoodsIssue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('issued_number')->nullable();
            $table->date('date_issued');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('issued_by');
            $table->text('description')->nullable();
            $table->string('status');
            $table->text('reject_reason')->nullable();
            $table->string('key_number')->nullable();
            $table->timestamps();            
            
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('goods_issues');
    }
}
