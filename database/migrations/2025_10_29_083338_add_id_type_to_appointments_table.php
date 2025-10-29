<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddIdTypeToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->enum('id_type', ['KTP', 'SIM', 'ID CARD'])
                ->default('KTP')
                ->after('id_card');
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn('id_type');
        });
    }
}
