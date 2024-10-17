<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('pic_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pic_id')->references('id')->on('users');
            $table->string('name');
            $table->string('purpose');
            $table->date('date');
            $table->time('time');
            $table->string('guest');
            $table->bigInteger('pic_dept');
            $table->string('doc');
            $table->string('selfie');
            $table->string('pic_approval');
            $table->string('dh_approval');
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
        Schema::dropIfExists('appointments');
    }
}