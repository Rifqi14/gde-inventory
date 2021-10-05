<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproverIdAndDistributionIdToDocumentCenterDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('approver_id')->nullable();

            $table->foreign('approver_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->dropForeign(['approver_id']);
            $table->dropColumn('approver_id');
        });
    }
}
