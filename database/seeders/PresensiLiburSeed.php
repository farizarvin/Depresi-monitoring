<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresensiLiburSeed extends Seeder
{
    public function run()
    {
        // Hari libur nasional + event sekolah
        // FORMAT: ket, dmulai, dselesai, bmulai, bselesai, jenjang[]
        $events = [

            // === Libur Nasional ===
            ['Tahun Baru Masehi',          1,  1,  1,  1, [1,2,3]],
            ['Isra Mi\'raj',               27, 27, 1,  1, [1,2,3]],
            ['Tahun Baru Imlek',           1,  1,  2,  2, [1,2,3]],
            ['Hari Raya Nyepi',            29, 29, 3,  3, [1,2,3]],
            ['Wafat Isa Almasih',          18, 18, 4,  4, [1,2,3]],
            ['Hari Buruh',                  1,  1, 5,  5, [1,2,3]],
            ['Kenaikan Isa Almasih',       29, 29, 5,  5, [1,2,3]],
            ['Hari Lahir Pancasila',        1,  1, 6,  6, [1,2,3]],
            ['Idul Adha',                   6,  6, 6,  6, [1,2,3]],
            ['Tahun Baru Hijriyah',        26, 26, 6,  6, [1,2,3]],
            ['Hari Kemerdekaan RI',        17, 17, 8,  8, [1,2,3]],
            ['Maulid Nabi',                 4,  4, 9,  9, [1,2,3]],
            ['Natal',                      25, 25,12, 12, [1,2,3]],

            // === Event Sekolah (Tambahan) ===
            ['Masa Pengenalan Lingkungan Sekolah (MPLS)',    15, 17, 7, 7, [1,2,3]],
            ['Ujian Tengah Semester (UTS)',                  10, 14, 10, 10, [2,3]],
            ['Ujian Akhir Semester (UAS)',                   2,  6,  12, 12, [2,3]],
            ['Rapat Guru',                                   30, 30, 6,  6, [3]],
            ['Kegiatan Parenting Day',                       22, 22, 4,  4, [1]],
            ['Study Tour',                                   5,  7,  9,  9, [2,3]],
            ['Class Meeting',                                12, 13, 6,  6, [1,2,3]],
            ['Pembagian Raport Semester',                    20, 20, 12, 12, [1,2,3]],
            ['Kegiatan Donor Darah Sekolah',                 3,  3,  5,  5, [3]],

            // Event Custom Biar Tidak Kosong Per Periode
            ['Libur Akhir Tahun Ajaran',                     25, 30, 6,  6, [1,2,3]],
            ['Kegiatan Pramuka Gabungan',                    14, 14, 3,  3, [2,3]],
        ];

        foreach ($events as $e) {
            DB::table('presensi_libur')->insert([
                'ket'             => $e[0],
                'tanggal_mulai'   => $e[1],
                'tanggal_selesai' => $e[2],
                'bulan_mulai'     => $e[3],
                'bulan_selesai'   => $e[4],
                'jenjang'         => json_encode($e[5]),
                'id_author'       => 1,
            ]);
        }
    }
}
