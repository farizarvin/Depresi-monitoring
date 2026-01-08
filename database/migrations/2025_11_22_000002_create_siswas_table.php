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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir', 50);
            $table->boolean('gender');
            $table->boolean('status')->default(true);
            $table->boolean('need_survey')->default(false);
            $table->boolean('is_depressed')->default(false);
            $table->boolean('need_selfcare')->default(false);
            $table->date('tanggal_lahir');
            $table->mediumText('alamat');
            $table->foreignId('id_user')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null')
            ->onUpdate('cascade');
            $table->foreignId('id_thak_masuk')
            ->constrained('tahun_akademik')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
