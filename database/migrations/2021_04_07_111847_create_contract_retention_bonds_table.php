<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractRetentionBondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_retention_bonds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contract_id')->nullable();
            $table->text('issued_by')->nullable();
            $table->double('validity_period')->default(0);
            $table->double('value')->default(0);
            $table->timestamps();
            $table->foreign('contract_id')->references('id')->on('contracts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contract_retention_bonds', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
        Schema::dropIfExists('contract_retention_bonds');
    }
}
