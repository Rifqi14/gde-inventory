<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedRejectReasonToSalaryReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_reports', function (Blueprint $table) {
            $table->text('approved_reason')->nullable();
            $table->text('reject_reason')->nullable();
            $table->unsignedBigInteger('approval_by')->nullable();
            $table->dateTime('approval_date')->nullable();

            $table->foreign('approval_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_reports', function (Blueprint $table) {
            $table->dropForeign(['approval_by']);
            $table->dropColumn(['approved_reason', 'reject_reason', 'approval_by', 'approval_date']);
        });
    }
}
