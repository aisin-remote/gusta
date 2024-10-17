<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('signed_by')->unsigned();
            $table->bigInteger('appointment_id')->unsigned();
            $table->foreign('signed_by')->references('id')->on('users');
            $table->foreign('appointment_id')->references('id')->on('appointments');
            $table->string('note')->nullable();
            $table->string('status');
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
        Schema::dropIfExists('approval_histories');
    }
}