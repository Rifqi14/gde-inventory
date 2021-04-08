<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('number')->nullable();
            $table->text('title')->nullable();
            $table->text('scope_of_work')->nullable();
            $table->text('contractor')->nullable();
            $table->text('contract_currency')->nullable();
            $table->double('contract_value')->default(0);
            $table->text('contract_pic')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('purchasing_id')->nullable();
            $table->date('contract_signing_date')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('work_start_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->date('work_end_date')->nullable();
            $table->unsignedBigInteger('insurance')->nullable();
            $table->unsignedBigInteger('warning_letter')->nullable();
            $table->text('attachment')->nullable();
            $table->unsignedBigInteger('progress')->nullable();
            $table->text('status')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->text('unit')->nullable();
            $table->text('exp_status')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
