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
        Schema::create('diary', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_presensi')
            ->constrained('presensi')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('swafoto');
            $table->string('swafoto_pred', 50);
            $table->string('catatan_pred', 50);
            $table->string('catatan_ket', 100);
            $table->mediumText('catatan');
            // $table->timestamps();
            $table->timestamp('waktu')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diary');
    }
};
