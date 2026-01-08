<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Siswa;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Password for all dev users
        $password = '12345678';

        DB::beginTransaction();
        try {
            // 1. Create Guru User
            $guruUser = User::firstOrCreate(
                ['username' => 'guru'],
                [
                    'email' => 'guru@dev.com',
                    'password' => $password, // Will be hashed by cast or mutator if set, but mostly usually handled by fill. Models cast: 'password' => 'hashed'
                    'role' => 'guru',
                ]
            );

            // Create Guru Profile if not exists
            if (!Guru::where('id_user', $guruUser->id)->exists()) {
                Guru::create([
                    'id_user' => $guruUser->id,
                    'nip' => '1234567890',
                    'nama_lengkap' => 'Guru Development',
                    'alamat' => 'Jalan Development No. 1',
                    'gender' => 1, // Laki-laki
                    'tgl_lahir' => '1990-01-01',
                ]);
            }

            // 2. Create Siswa User
            $siswaUser = User::firstOrCreate(
                ['username' => 'siswa'],
                [
                    'email' => 'siswa@dev.com',
                    'password' => $password,
                    'role' => 'siswa',
                ]
            );

            // Get a class and academic year for the student
            $kelas = Kelas::first();
            $thak = TahunAkademik::where('status', true)->first() ?? TahunAkademik::first();

            if (!$kelas || !$thak) {
                // Should ideally create them if missing, but assuming seeders ran already since app is running
                $this->command->warn('Kelas or TahunAkademik not found. Skipping student class enrollment.');
            }

            // Create Siswa Profile if not exists
            if (!Siswa::where('id_user', $siswaUser->id)->exists()) {
                $siswa = Siswa::create([
                    'id_user' => $siswaUser->id,
                    'nisn' => '0011223344',
                    'nama_lengkap' => 'Siswa Development',
                    'tempat_lahir' => 'Jakarta',
                    'tanggal_lahir' => '2010-01-01',
                    'alamat' => 'Jalan Siswa No. 1',
                    'gender' => 0, // Perempuan
                    'status' => true, // Active
                    'id_thak_masuk' => $thak ? $thak->id : null,
                ]);

                // Enroll in a class
                if ($kelas && $thak) {
                    RiwayatKelas::create([
                        'id_siswa' => $siswa->id,
                        'id_kelas' => $kelas->id,
                        'id_thak' => $thak->id,
                        'status' => 'NW',
                        'active' => true,
                    ]);
                }
            }

            DB::commit();
            $this->command->info('Development users created successfully.');
            $this->command->info('Guru: username=guru, password=12345678');
            $this->command->info('Siswa: username=siswa, password=12345678');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error creating users: ' . $e->getMessage());
        }
    }
}
