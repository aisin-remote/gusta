    <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacilityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('snack_kering')->nullable();
            $table->integer('snack_basah')->nullable();
            $table->integer('makan_siang')->nullable();
            $table->integer('permen')->nullable();
            $table->integer('kopi')->nullable();
            $table->integer('teh')->nullable();
            $table->integer('soft_drink')->nullable();
            $table->integer('air_mineral')->nullable();
            $table->integer('helm')->nullable();
            $table->integer('handuk')->nullable();
            $table->integer('speaker')->nullable();
            $table->integer('speaker_wireless')->nullable();
            $table->integer('mobil')->nullable();
            $table->integer('motor')->nullable();
            $table->integer('mini_bus')->nullable();
            $table->integer('bus')->nullable();
            $table->string('status')->default('pending');
            $table->bigInteger('appointment_id')->unsigned();
            $table->foreign('appointment_id')->references('id')->on('appointments');
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
        Schema::dropIfExists('facility_details');
    }
}