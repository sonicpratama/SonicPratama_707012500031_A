<?php

namespace Modules\Pengaduan\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Layanan\Models\Layanan;
use Modules\Pengaduan\Models\Pengaduan;

/**
 * @extends Factory<Pengaduan>
 */
class PengaduanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Pengaduan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'layanan_id' => Layanan::factory(),
            'deskripsi_pengaduan' => fake()->sentence(12),
            'file_bukti' => null,
            'nama_file' => null,
        ];
    }
}
