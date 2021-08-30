<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalMatrixGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_matrix_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('matrix_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->timestamps();

            $table->foreign('matrix_id')->references('id')->on('document_external_matrices')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_external_matrix_groups');
    }
}
