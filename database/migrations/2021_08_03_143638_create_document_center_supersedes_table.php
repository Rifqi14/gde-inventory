<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCenterSupersedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_center_supersedes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_center_document_id')->nullable();
            $table->unsignedBigInteger('document_center_id')->nullable();
            $table->text('supersede_remark')->nullable();
            $table->timestamps();

            $table->foreign('document_center_document_id')->references('id')->on('document_center_documents')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_center_id')->references('id')->on('document_centers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_center_supersedes');
    }
}
