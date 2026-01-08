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
        Schema::table('diary', function (Blueprint $table) {
            $table->text('swafoto_pred')->change();
            $table->text('catatan_pred')->change();
            $table->text('catatan_ket')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->string('swafoto_pred', 50)->change();
            $table->string('catatan_pred', 50)->change();
            $table->string('catatan_ket', 100)->change();
        });
    }
};
