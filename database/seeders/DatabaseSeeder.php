<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        // admin
        User::factory()->create([
            'name' => 'Admin utama',
            'email' => 'admin@app.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        // petugas
        User::factory()->create([
            'name' => 'Petugas utama',
            'email' => 'petugas@app.com',
            'password' => bcrypt('petugas123'),
            'role' => 'petugas'
        ]);

        // peminjam
        User::factory()->create([
            'name' => 'Peminjam',
            'email' => 'peminjam@app.com',
            'password' => bcrypt('peminjam123'),
            'role' => 'peminjam'
        ]);
    }
}
