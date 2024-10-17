<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('appointment_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->foreign('room_id')->references('id')->on('rooms');
            $table->date('booking_date');
            $table->time('booking_time');
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
        Schema::dropIfExists('room_details');
    }
}