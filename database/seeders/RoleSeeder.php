<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=
        [
            ["name"=>"admin"],
            ["name"=>"siswa"],
            ["name"=>"guru_bk"],
            ["name"=>"psikolog"]
        ];
        Role::insert($data);
    }
}
