<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaryReportDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_report_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('salary_report_id')->nullable();
            $table->text('description')->nullable();
            $table->float('total')->nullable();
            $table->integer('type')->nullable();
            $table->integer('is_added')->default(0);
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->timestamps();

            $table->foreign('salary_report_id')->references('id')->on('salary_reports')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_report_details');
    }
}