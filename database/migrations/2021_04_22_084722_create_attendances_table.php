<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('attendance_date');
            $table->dateTime('attendance_in')->nullable();
            $table->dateTime('attendance_out')->nullable();
            $table->string('status', 30)->nullable();
            $table->float('working_time')->nullable();
            $table->float('over_time')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('working_shift_id');
            $table->string('day', 5)->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('working_shift_id')->references('id')->on('working_shifts')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}