<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_user=
        [
            'username'=>'admin',
            'password'=>'12345678',
            'email'=>'admin@example.com',
            'role'=>'admin'
        ];
        $user=User::create($data_user);
        $data_admin=
        [
            'nama_lengkap'=>'Fariz Jawa',
            'gender'=>1,
            'tanggal_lahir'=>'2004-03-22',
            'alamat'=>'Ngaliyan',
            'id_user'=>$user->id
        ];
        Admin::create($data_admin);

    }
}
