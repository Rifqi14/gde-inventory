<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_revisions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_external_id')->nullable();
            $table->unsignedBigInteger('sheet_size_id')->nullable();
            $table->integer('nos_of_pages')->nullable();
            $table->string('revision_no')->nullable();
            $table->text('revision_remark')->nullable();
            $table->string('contractor_revision_no')->nullable();
            $table->string('issue_status')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('document_external_id')->references('id')->on('document_externals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sheet_size_id')->references('id')->on('document_external_sheet_sizes')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('document_external_revisions');
    }
}
