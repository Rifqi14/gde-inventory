<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_workflows', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_id')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('label_group')->nullable();
            $table->integer('nos_of_pages')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('need_approval')->nullable();
            $table->boolean('sla')->nullable();
            $table->date('sla_dates')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            $table->foreign('workflow_id')->references('id')->on('workflows')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_workflows');
    }
}
