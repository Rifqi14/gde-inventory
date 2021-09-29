<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_revision_id')->nullable();
            $table->string('status')->nullable();
            $table->integer('revise_number')->nullable();
            $table->text('reason')->nullable();
            $table->string('attachment_name')->nullable();
            $table->text('attachment')->nullable();
            $table->timestamps();

            $table->foreign('document_revision_id')->references('id')->on('document_external_revisions')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_external_logs');
    }
}
