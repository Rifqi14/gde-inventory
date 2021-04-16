<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('number')->nullable();
            $table->text('subject')->nullable();
            $table->text('rule')->nullable();
            $table->text('est_currency')->nullable();
            $table->double('est_value')->default(0);
            $table->double('technical')->default(0);
            $table->double('financial')->default(0);
            $table->text('tor')->nullable();
            $table->double('duration')->default(0);
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->text('adb')->nullable();
            $table->text('status')->nullable();
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
        Schema::dropIfExists('purchasings');
    }
}
