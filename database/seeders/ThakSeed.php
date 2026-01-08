<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThakSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $current=now()->year;
        $start=2020;
        $end=$current+2;

        $data=[];

        while($start <= $end)
        {
            array_push($data, [
                'current'=>($start==$current),
                'status'=>($start>=$current),
                'tanggal_mulai'=>$start."-03-01",
                'tanggal_selesai'=>++$start."-02-01",
                'nama_tahun'=>($start-1)."/".$start
            ]);
        }
        TahunAkademik::insert($data);
        
    }
}
