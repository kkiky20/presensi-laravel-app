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
            $table->char('kode_izin', 9)->primary();
            $table->char('nik', 6)->nullable();
            $table->date('tgl_izin_dari')->nullable();
            $table->date('tgl_izin_sampai')->nullable();
            $table->char('status', 1)->comment('i = izin, s = sakit')->nullable();
            $table->char('kode_cuti', 3)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('doc_sid', 255)->nullable();
            $table->char('status_approved', 1)->comment('0 = Pending, 1 = Disetujui, 2 = Ditolak')->default('0')->nullable();
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
