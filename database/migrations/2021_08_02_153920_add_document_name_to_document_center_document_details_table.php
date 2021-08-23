<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentNameToDocumentCenterDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_center_document_details', function (Blueprint $table) {
            $table->string('document_name')->nullable();
            $table->integer('file_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_center_document_details', function (Blueprint $table) {
            $table->dropColumn(['document_name', 'file_size']);
        });
    }
}
