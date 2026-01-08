<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAjaranSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $year0=Carbon::now()->format('Y');
        $year1=Carbon::now()->addYear()->format('Y');
        $date0=Carbon::now()->format('Y-m-d');
        $date1=Carbon::now()->addYear()->format('Y-m-d');
        $data=
        [
            'tahun_mulai'=>"$year0",
            'tahun_akhir'=>"$year1",
            'tanggal_mulai'=>$date0,
            'tanggal_akhir'=>$date1,
            'is_aktif'=>true,
            'status'=>'aktif'
        ];
        TahunAjaran::create($data);
    }
}
