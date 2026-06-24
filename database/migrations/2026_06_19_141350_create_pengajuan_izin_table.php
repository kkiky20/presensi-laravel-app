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
        Schema::create('pengajuan_izin', function (Blueprint $table) {
            $table->id('id');
            $table->char('nik', 6);
            $table->date('tgl_izin');
            $table->char('status', 1)->comment('i = izin, s = sakit');
            $table->string('keterangan', 255);
            $table->char('status_approved', 1)->comment('0 = Pending, 1 = Disetujui, 2 = Ditolak')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izin');
    }
};
