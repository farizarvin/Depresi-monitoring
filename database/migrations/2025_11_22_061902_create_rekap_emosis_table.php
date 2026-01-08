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
        Schema::create('rekap_emosi', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->boolean('hasil');
            $table->mediumText('rekap_emoji');
            $table->foreignId('id_siswa')
            ->constrained('siswa')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->timestamp('waktu')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_emosi');
    }
};
