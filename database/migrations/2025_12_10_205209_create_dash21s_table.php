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
        Schema::create('dash21s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_siswa')->nullable()->constrained('siswa')->onDelete('set null')->onUpdate('cascade');
            $table->integer('depression_score')->default(0); // Added to support the logic requirement
            $table->boolean('is_depressed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dash21s');
    }
};
