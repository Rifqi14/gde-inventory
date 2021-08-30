<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_center_id')->nullable();
            $table->text('document_path')->nullable();
            $table->date('issued_date')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->integer('revision')->nullable();
            $table->string('status')->nullable();
            $table->text('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('document_center_id')->references('id')->on('document_centers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_center_documents');
    }
}
