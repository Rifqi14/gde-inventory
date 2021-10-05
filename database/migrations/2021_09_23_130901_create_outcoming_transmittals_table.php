<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutcomingTransmittalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outcoming_transmittals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('transmittal_no');
            $table->date('transmittal_date');
            $table->string('gde_contract_no')->nullable();
            $table->string('gde_contract_title');
            $table->string('transmittal_title');
            $table->text('transmittal_remark')->nullable();
            $table->text('sender');
            $table->unsignedBigInteger('contractor_group_id');
            $table->text('sender_address');
            $table->text('recipient_address');
            $table->unsignedBigInteger('issued_by');
            $table->string('status');
            $table->text('sender_signed_copy')->nullable();
            $table->text('recipient_signed_copy')->nullable();
            $table->unsignedBigInteger('sender_signed_copy_uploaded_by')->nullable();
            $table->unsignedBigInteger('recipient_signed_copy_uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('contractor_group_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('issued_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sender_signed_copy_uploaded_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('recipient_signed_copy_uploaded_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outcoming_transmittals');
    }
}
