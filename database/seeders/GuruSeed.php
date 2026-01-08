<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class GuruSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 dummy teachers
        Guru::factory()
            ->count(10)
            ->create();
            
        // Ensure the manually created one from before is still there (optional) or just rely on factory.
        // Let's just use the factory for consistency and bulk creation.
        // If we want specific users, we can add them here.
    }
}
