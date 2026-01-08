<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SiswaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_siswa=
        [
            [
                'nisn' => '0056712390',
                'nama_lengkap' => 'Aldi Saputra',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '2007-01-12',
                'alamat' => 'Jl. Melati No. 12, Bandung',
                'gender' => 1,
            ],
            [
                'nisn' => '0045589012',
                'nama_lengkap' => 'Rian Firmansyah',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '2007-03-25',
                'alamat' => 'Jl. Kenanga No. 55, Surabaya',
                'gender' => 1,
            ],
            [
                'nisn' => '0067894521',
                'nama_lengkap' => 'Siti Ramadhani',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2006-11-03',
                'alamat' => 'Jl. Mawar No. 27, Jakarta Selatan',
                'gender' => 0,
            ],
            [
                'nisn' => '0071123345',
                'nama_lengkap' => 'Putri Adelia',
                'tempat_lahir' => 'Bekasi',
                'tanggal_lahir' => '2007-08-19',
                'alamat' => 'Perum Griya Indah Blok D2, Bekasi',
                'gender' => 0,
            ],
            [
                'nisn' => '0098897765',
                'nama_lengkap' => 'Fahri Nugraha',
                'tempat_lahir' => 'Medan',
                'tanggal_lahir' => '2006-05-09',
                'alamat' => 'Jl. Karya Bakti No. 88, Medan',
                'gender' => 1,
            ],
        ];
        $year=now()->year;
        $thak_aktif=TahunAkademik::where('current', true)->first();
        
        foreach($data_siswa as $key=>$sis)
        {
            $data=
            [
                'username'=>$sis['nisn'],
                'password'=>"Nubi_".Carbon::parse($sis['tanggal_lahir'])->format('dmY'),
                'email'=>implode(explode(' ', $sis['nama_lengkap']))."@example.com",
                'role'=>'siswa',
            ];
            $user=User::create($data);
            $sis['id_user']=$user->id;
            $sis['id_thak_masuk']=$thak_aktif->id;
            $data_siswa[$key]=$sis;
        }

        Siswa::insert($data_siswa);

        
    }
}
