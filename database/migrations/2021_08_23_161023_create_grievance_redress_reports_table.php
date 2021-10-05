<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrievanceRedressReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grievance_redress_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grievance_id')->nullable();
            $table->text('description')->nullable();
            $table->text('status')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->integer('finance')->nullable();
            $table->text('pic')->nullable();
            $table->text('attachment')->nullable();
            $table->text('comment')->nullable();
            $table->text('attachment_comment')->nullable();
            $table->timestamps();

            $table->foreign('grievance_id')->references('id')->on('grievance_redress')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grievance_redress_reports');
    }
}
