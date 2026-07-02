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
        Schema::table('konfigurasi_jk_dept_detail', function (Blueprint $table) {
            $table->foreign('kode_jk_dept')
                ->references('kode_jk_dept')
                ->on('konfigurasi_jk_dept')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konfigurasi_jk_dept_detail', function (Blueprint $table) {
            //
        });
    }
};
