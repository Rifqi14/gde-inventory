<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_centers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('site_id')->nullable();
            $table->string('document_number')->nullable();
            $table->text('discipline')->nullable();
            $table->text('company')->nullable();
            $table->date('first_issue')->nullable();
            $table->string('status')->nullable();
            $table->string('menu')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('equipment_id')->references('id')->on('equipment')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('area_id')->references('id')->on('areas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('site_id')->references('id')->on('sites')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('dc_categorys')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_centers');
    }
}
