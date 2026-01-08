<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use Carbon\CarbonPeriod;
use App\Models\Presensi;

class PresensiSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Siswa::all();

        $idThak = TahunAkademik::where('current', true)->first()?->id ?? 1;

        // Range tanggal: 1 Maret 2025 – 1 Februari 2026
        $period = CarbonPeriod::create('2025-03-01', '2026-02-01');

        // Hari libur nasional (tambahin sendiri kalau sekolah punya libur internal)
        $tanggalMerah = [
            '2025-03-31',
            '2025-04-18',
            '2025-04-20', '2025-04-21', '2025-04-22',
            '2025-05-01', '2025-05-12',
            '2025-05-25', '2025-05-26',
            '2025-06-01', '2025-06-06',
            '2025-08-17',
            '2025-09-05',
            '2025-12-25', '2025-12-26',
            '2026-01-01', '2026-01-17',
        ];

        $statusOptions = ['H', 'I', 'S', 'A'];
        $reasonOptions = [
            'Sakit',
            'Izin urusan keluarga',
            'Periksa ke dokter',
            'Menghadiri acara keagamaan',
            'Keperluan penting lain-lain',
        ];

        foreach ($period as $date) {

            // skip Sabtu/Minggu
            if ($date->isWeekend()) {
                continue;
            }

            // skip tanggal merah
            if (in_array($date->format('Y-m-d'), $tanggalMerah)) {
                continue;
            }

            foreach ($students as $siswa) {

                // random status
                $status = $statusOptions[array_rand($statusOptions)];

                // isi ket kalau I atau S
                $ket = null;
                if (in_array($status, ['I', 'S'])) {
                    $ket = $reasonOptions[array_rand($reasonOptions)];
                }

                Presensi::create([
                    'status'    => $status,
                    'doc_path'  => null,
                    'ket'       => $ket,
                    'id_siswa'  => $siswa->id,
                    'id_thak'   => $idThak,
                    'waktu'     =>$date
                ]);
            }
        }
    }
}
