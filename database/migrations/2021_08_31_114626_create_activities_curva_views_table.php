<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesCurvaViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_curva_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('activity')->nullable();
            $table->date('start_date')->nullable();
            $table->date('finish_date')->nullable();
            $table->text('location')->nullable();
            $table->text('type')->nullable();
            $table->text('path')->nullable();
            $table->timestamp('start_update')->nullable();
            $table->timestamp('last_update')->nullable();
            $table->unsignedBigInteger('sort')->nullable();
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
        Schema::dropIfExists('activities_curva_views');
    }
}
