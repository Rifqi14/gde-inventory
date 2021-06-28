<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoodsIssueDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_issue_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('goods_issue_id');            
            $table->text('file');
            $table->string('document_name')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('goods_issue_id')->references('id')->on('goods_issues')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_goods_issue_documents');
    }
}
