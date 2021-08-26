<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSomeTableFromDocumentCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_centers', function (Blueprint $table) {
            $table->dropForeign(['equipment_id']);
            $table->dropForeign(['area_id']);
            $table->dropForeign(['site_id']);
            $table->dropColumn(['equipment_id', 'area_id', 'site_id', 'discipline', 'company', 'first_issue']);

            $table->unsignedBigInteger('document_type_id')->nullable();
            $table->unsignedBigInteger('organization_code_id')->nullable();
            $table->unsignedBigInteger('unit_code_id')->nullable();
            $table->text('remark')->nullable();

            $table->foreign('document_type_id')->references('id')->on('document_types')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_code_id')->references('id')->on('organization_codes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('unit_code_id')->references('id')->on('unit_codes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_centers', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->text('discipline')->nullable();
            $table->text('company')->nullable();
            $table->date('first_issue')->nullable();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');

            
            $table->dropForeign(['document_type_id']);
            $table->dropForeign(['organization_code_id']);
            $table->dropForeign(['unit_code_id']);
            $table->dropColumn(['document_type_id', 'organization_code_id', 'unit_code_id', 'remark']);
        });
    }
}
