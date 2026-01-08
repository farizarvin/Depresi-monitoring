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
        Schema::create('presensi_libur', function (Blueprint $table) {
            $table->id();
            $table->string('ket');
            $table->unsignedTinyInteger('tanggal_mulai');
            $table->unsignedTinyInteger('tanggal_selesai');
            $table->unsignedTinyInteger('bulan_mulai');
            $table->unsignedTinyInteger('bulan_selesai');
            $table->json("jenjang");
            $table->foreignId('id_author')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null')
            ->onUpdate('cascade');
            $table->timestamp('waktu')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_libur');
    }
};
