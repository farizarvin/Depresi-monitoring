<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KelasSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades=['X', 'XI', 'XII'];
        $indexes=['A', 'B', 'C', 'D', 'F'];
        $data=[];
        $id=1;
        $jurusan=
        [
            'A'=>'IPA',
            'B'=>'IPA',
            'C'=>'IPA',
            'D'=>'IPS',
            'E'=>'IPS',
            'F'=>'IPS'
        ];
        foreach($grades as $g)
        {
            foreach($indexes as $i)
            {
                array_push($data, [
                    'nama'=>"$g-$i",
                    'jenjang'=>$id,
                    'jurusan'=>$jurusan[$i]
                ]);
            }
            $id++;
        }

        Kelas::insert($data);
    }
}
