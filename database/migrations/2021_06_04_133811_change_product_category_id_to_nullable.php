<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductCategoryIdToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_borrowings', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_borrowings', function (Blueprint $table) {
            $table->unsignedBigInteger('product_category_id')->change();
        });
    }
}