<?php

use Doctrine\DBAL\Schema\Schema as SchemaSchema;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexNumberOnTableBusinessTrips extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('business_trips', function(Blueprint $table){
            $table->integer('index_number')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('business_trips', function(Blueprint $table){
            $table->dropColumn('index_number');
        });
    }
}
