<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterVoidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_voids', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_center_document_id')->nullable();
            $table->text('void_remark')->nullable();
            $table->timestamps();

            $table->foreign('document_center_document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->string('document_type')->nullable();
            $table->string('transmittal_status')->nullable()->default('Waiting for Issue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'transmittal_status']);
        });

        Schema::dropIfExists('document_center_voids');
    }
}
