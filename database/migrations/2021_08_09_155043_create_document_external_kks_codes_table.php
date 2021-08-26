<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalKksCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_external_kks_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('document_external_kks_category_id')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();

            $table->foreign('document_external_kks_category_id')->references('id')->on('document_external_kks_categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_external_kks_codes');
    }
}
