<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchasing_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('purchasing_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->timestamps();
            $table->foreign('purchasing_id')->references('id')->on('purchasings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchasing_users');
    }
}
