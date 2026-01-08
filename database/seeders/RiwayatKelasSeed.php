<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\RiwayatKelas;
use App\Models\TahunAkademik;

class RiwayatKelasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $students = Siswa::all();

        $idThak = TahunAkademik::where('current', true)->first()?->id ?? 1;

        $statusOptions = ['MM', 'NW'];

        $kelasId = 1; // increment per siswa

        foreach ($students as $siswa) {

            RiwayatKelas::create([
                'id_kelas' => $kelasId,
                'id_siswa' => $siswa->id,
                'id_thak'  => $idThak,
                'status'   => $statusOptions[array_rand($statusOptions)],
                'active'   => true
            ]);

            $kelasId++;
        }
    }
}
