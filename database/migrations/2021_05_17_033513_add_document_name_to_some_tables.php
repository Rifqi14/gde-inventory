<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentNameToSomeTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('goods_receipt_assets', function (Blueprint $table) {
            $table->string('document_name')->nullable();
        });
        Schema::table('product_borrowing_documents', function (Blueprint $table) {
            $table->string('document_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goods_receipt_assets', function (Blueprint $table) {
            $table->dropColumn('document_name');
        });
        Schema::table('product_borrowing_documents', function (Blueprint $table) {
            $table->dropColumn('document_name');
        });
    }
}