<?php

namespace Database\Seeders;

use App\Models\Angkatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=
        [
            'tahun'=>now()->format('Y'),
            'tanggal_mulai'=>now()->format('Y-m-d'),
            'tanggal_akhir'=>now()->addYear(3)->format('Y-m-d'),
            'id_tahun_mulai'=>1
        ];
        Angkatan::create($data);
    }
}
