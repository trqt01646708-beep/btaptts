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
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('1234'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Thanh User',
            'username' => 'thanh123',
            'email' => 'thanh123@example.com',
            'password' => bcrypt('1234'),
            'role' => 'user',
        ]);
    }
}
