<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalRevisionSupersedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_revision_supersedes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('revision_id')->nullable();
            $table->unsignedBigInteger('document_external_id')->nullable();
            $table->text('supersede_remark')->nullable();
            $table->timestamps();

            $table->foreign('revision_id')->references('id')->on('document_external_revisions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('document_external_revision_supersedes');
    }
}
