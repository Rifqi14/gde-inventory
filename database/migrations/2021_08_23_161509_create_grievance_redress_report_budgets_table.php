<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrievanceRedressReportBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grievance_redress_report_budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('report_id')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->double('value')->default(0);
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('grievance_redress_reports')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grievance_redress_report_budgets');
    }
}
