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
    public function run()
    {
        
        // admin
        user::create([
            'name' => 'admin utama',
            'email' => 'admin@app.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // petugas
        user::create([
            'name' => 'petugas utama',
            'email'=>'petugas@app.com',
            'password' => bcrypt('password'),
            'role' => 'petugas'
        ]);

        // peminjam
        user::create([
            'name' => 'peminjam utama',
            'email'=>'peminjam@app.com',
            'password' => bcrypt('password'),
            'role' => 'peminjam'
        ]);


        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
