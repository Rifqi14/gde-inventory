<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_center_document_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('revise_number')->nullable();
            $table->text('reason')->nullable();
            $table->string('attachment_name')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();

            $table->foreign('document_center_document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_center_logs');
    }
}
