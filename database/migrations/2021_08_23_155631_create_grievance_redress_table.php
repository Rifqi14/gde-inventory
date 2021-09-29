<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrievanceRedressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grievance_redress', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('number')->nullable();
            $table->date('date')->nullable();
            $table->text('time')->nullable();
            $table->text('media')->nullable();
            $table->text('unit')->nullable();
            $table->text('attachment')->nullable();
            $table->text('complainant')->nullable();
            $table->text('gender')->nullable();
            $table->text('id_number')->nullable();
            $table->text('address')->nullable();
            $table->text('phone')->nullable();
            $table->text('fax')->nullable();
            $table->text('email')->nullable();
            $table->text('affiliation')->nullable();
            $table->text('complaint_type')->nullable();
            $table->text('location')->nullable();
            $table->text('complaint_desc')->nullable();
            $table->text('idm_name')->nullable();
            $table->text('idm_id_number')->nullable();
            $table->text('idm_address')->nullable();
            $table->text('idm_phone')->nullable();
            $table->text('idm_fax')->nullable();
            $table->text('idm_email')->nullable();
            $table->text('idm_attachment')->nullable();
            $table->unsignedBigInteger('created_user')->nullable();
            $table->unsignedBigInteger('updated_user')->nullable();
            $table->text('status')->nullable();
            $table->text('complaint_type_other')->nullable();
            $table->text('approval_status')->nullable();
            $table->date('queue_date')->nullable();
            $table->date('declined_date')->nullable();
            $table->date('active_date')->nullable();
            $table->date('cleared_date')->nullable();
            $table->text('reporter')->nullable();
            $table->text('comment')->nullable();
            $table->text('attachment_comment')->nullable();
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
        Schema::dropIfExists('grievance_redress');
    }
}
