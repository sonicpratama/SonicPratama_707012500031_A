<?php

namespace Modules\Layanan\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Layanan\Models\Layanan;

/**
 * @extends Factory<Layanan>
 */
class LayananFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Layanan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_layanan' => 'Layanan ' . fake()->unique()->words(3, true),
            'kategori_layanan' => fake()->randomElement(array_keys(Layanan::KATEGORI)),
            'perangkat_daerah' => 'Dinas ' . fake()->words(2, true),
            'deskripsi' => fake()->sentence(10),
            'tingkat_kematangan' => fake()->numberBetween(1, 5),
        ];
    }
}
