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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['H', 'I', 'S', 'A']);
            $table->string('doc_path')->nullable();
            $table->mediumText('ket')->nullable();
            $table->foreignId('id_siswa')
            ->constrained('siswa')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreignId('id_thak')
            ->constrained('tahun_akademik')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->timestamp('waktu')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
