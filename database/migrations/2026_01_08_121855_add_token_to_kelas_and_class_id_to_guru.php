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
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('token', 10)->unique()->nullable()->after('nama');
        });

        Schema::table('guru', function (Blueprint $table) {
            $table->foreignId('id_kelas')->nullable()->constrained('kelas')->nullOnDelete()->after('id_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropForeign(['id_kelas']);
            $table->dropColumn('id_kelas');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('token');
        });
    }
};
