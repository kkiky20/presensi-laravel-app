<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('konfigurasi_jamkerja', function (Blueprint $table) {
            $table->id();
            $table->char('nik', 10)->nullable();
            $table->string('hari', 10)->nullable();
            $table->char('kode_jam_kerja', 4)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_jamkerja');
    }
};
