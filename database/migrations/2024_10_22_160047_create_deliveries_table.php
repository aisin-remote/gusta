<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('purpose');
            $table->date('date');
            $table->time('time');
            $table->integer('guest');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('pic_id');
            $table->unsignedBigInteger('pic_dept');
            $table->string('doc')->nullable();
            $table->string('selfie')->nullable();
            $table->string('pic_approval')->default('pending');
            $table->string('dh_approval')->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pic_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('pic_dept')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
