<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attendance_id')->nullable();
            $table->timestamp('request_date')->nullable();
            $table->text('request_reason')->nullable();
            $table->string('type')->nullable();
            $table->integer('revision_number')->nullable();
            $table->string('status')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            $table->foreign('attendance_id')->references('id')->on('attendances')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_requests');
    }
}