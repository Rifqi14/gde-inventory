<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchasing_id')->nullable();
            $table->unsignedBigInteger('budget_id')->nullable();
            $table->double('value')->default(0);
            $table->timestamps();
            $table->foreign('purchasing_id')->references('id')->on('purchasings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('budget_id')->references('id')->on('budgets')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_budgets');
    }
}
