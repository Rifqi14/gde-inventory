<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_matrices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_external_id')->nullable();
            $table->string('matrix_label')->nullable();
            $table->string('matrix_sla')->nullable();
            $table->string('matrix_days')->nullable();
            $table->timestamps();

            $table->foreign('document_external_id')->references('id')->on('document_externals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_external_matrices');
    }
}
