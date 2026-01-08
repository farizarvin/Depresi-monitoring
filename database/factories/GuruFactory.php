<?php

namespace Database\Factories;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuruFactory extends Factory
{
    protected $model = Guru::class;

    public function definition(): array
    {
        return [
            'id_user' => User::factory()->state(['role' => 'guru']),
            'nip' => $this->faker->unique()->numerify('########'),
            'nama_lengkap' => $this->faker->name(),
            'alamat' => $this->faker->address(),
            'gender' => $this->faker->boolean(),
            'tgl_lahir' => $this->faker->date(),
        ];
    }
}
