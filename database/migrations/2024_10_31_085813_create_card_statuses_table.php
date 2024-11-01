<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('card_id')->unsigned();
            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('guest_id')->references('id')->on('guests');
            $table->string('serial')->nullable();
            $table->enum('status', ['ready','used']);
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
        Schema::dropIfExists('card_statuses');
    }
}
