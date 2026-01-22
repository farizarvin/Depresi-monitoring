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
        Schema::table('presensi_libur', function (Blueprint $table) {
            $table->date('tanggal_mulai')->change();
            $table->date('tanggal_selesai')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensi_libur', function (Blueprint $table) {
            $table->unsignedTinyInteger('tanggal_mulai')->change();
            $table->unsignedTinyInteger('tanggal_selesai')->change();
        });
    }
};
