<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSomeTableToDocumentCenterDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_center_documents', function (Blueprint $table) {
            $table->text('transmittal_no')->nullable();
            $table->string('issue_purpose')->nullable();
            $table->dropColumn(['issued_date']);
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
            $table->dropColumn(['transmittal_no', 'issue_purpose']);
            $table->date('issued_date')->nullable();
        });
    }
}
