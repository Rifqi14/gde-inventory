<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentExternalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_externals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('document_number')->nullable();
            $table->text('document_title')->nullable();
            $table->unsignedBigInteger('site_code_id')->nullable();
            $table->unsignedBigInteger('discipline_code_id')->nullable();
            $table->unsignedBigInteger('kks_category_id')->nullable();
            $table->unsignedBigInteger('kks_code_id')->nullable();
            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->unsignedBigInteger('originator_code_id')->nullable();
            $table->unsignedBigInteger('phase_code_id')->nullable();
            $table->integer('document_sequence')->nullable();
            $table->string('document_category_id')->nullable();
            $table->text('contractor_document_number')->nullable();
            $table->unsignedBigInteger('contractor_name_id')->nullable();
            $table->unsignedBigInteger('contractor_group_id')->nullable();
            $table->date('planned_ifi_ifa_date')->nullable();
            $table->date('planned_ifc_ifu_date')->nullable();
            $table->date('planned_afc_date')->nullable();
            $table->date('planned_ab_date')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->text('document_remark')->nullable();
            $table->timestamps();

            $table->foreign('site_code_id')->references('id')->on('document_external_site_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('discipline_code_id')->references('id')->on('document_external_discipline_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kks_category_id')->references('id')->on('document_external_kks_categories')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kks_code_id')->references('id')->on('document_external_kks_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('document_type_id')->references('id')->on('document_external_document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('originator_code_id')->references('id')->on('document_external_originator_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('phase_code_id')->references('id')->on('document_external_phase_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contractor_name_id')->references('id')->on('document_external_contractor_names')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('contractor_group_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('document_externals');
    }
}
