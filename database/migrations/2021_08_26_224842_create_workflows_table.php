<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_external_id')->nullable();
            $table->unsignedBigInteger('revision_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('complete_date')->nullable();
            $table->string('return_code')->nullable();
            $table->string('current_status')->nullable();
            $table->string('next_status')->nullable();
            $table->timestamps();

            $table->foreign('document_external_id')->references('id')->on('document_externals')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('workflows');
    }
}
