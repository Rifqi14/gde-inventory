<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('budget_id');
            $table->string('type', 15);
            $table->double('weight')->default(0);
            $table->double('total')->default(0);
            $table->foreign('budget_id')->references('id')->on('budgets')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('budget_details', function (Blueprint $table) {
            $table->dropForeign(['budget_id']);
        });
        Schema::dropIfExists('budget_details');
    }
}
