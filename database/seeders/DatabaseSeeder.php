<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Layanan\Database\Seeders\LayananDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Administrator',
            'email' => 'admin@sispbe.test',
            'password' => 'admin123',
        ]);

        User::factory()->create([
            'name' => 'Warga Bandung',
            'email' => 'warga@sispbe.test',
            'password' => 'warga123',
        ]);

        $this->call([
            LayananDatabaseSeeder::class,
        ]);
    }
}
