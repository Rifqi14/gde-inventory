<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentOutcomingTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_outcoming_transmittals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('outcoming_transmittal_id');
            $table->unsignedBigInteger('revision_id');

            $table->foreign('outcoming_transmittal_id')->references('id')->on('outcoming_transmittals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('revision_id')->references('id')->on('document_external_revisions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_outcoming_transmittals');
    }
}
