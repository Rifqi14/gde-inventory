<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSenderFileNameAndRecipientFileNameToOutcomingTransmittals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('outcoming_transmittals', function (Blueprint $table) {
            $table->string('sender_file_name')->nullable();
            $table->string('recipient_file_name')->nullable();
            $table->string('sender_alias')->nullable();
            $table->string('recipient_alias')->nullable();
            $table->string('transmittal_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outcoming_transmittals', function (Blueprint $table) {
            $table->dropColumn('sender_file_name');
            $table->dropColumn('recipient_file_name');
            $table->dropColumn('sender_alias');
            $table->dropColumn('recipient_alias');
            $table->string('transmittal_no')->change();
        });
    }
}
