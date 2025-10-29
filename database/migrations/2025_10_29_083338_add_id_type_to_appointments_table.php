<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Tambah kolom enum id_type dengan nilai yang diizinkan
            // Jika DB Anda tidak nyaman dengan ENUM, ganti ke string(20) + cek di app layer.
            $table->enum('id_type', ['KTP', 'SIM', 'ID CARD'])
                ->default('KTP')
                ->after('purpose'); // Sesuaikan posisi kolom jika perlu
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('id_type');
        });
    }
};
