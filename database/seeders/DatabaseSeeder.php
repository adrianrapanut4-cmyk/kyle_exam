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
        User::factory(10)->create();

        // Create test accounts
        User::create([
            'name' => 'John Ford',
            'email' => 'johnford@example.com',
            'password' => bcrypt('password123'),
            'bio' => 'Software developer and tech enthusiast',
        ]);

        User::create([
            'name' => 'Adrian Kyle',
            'email' => 'adriankyler@gmail.com',
            'password' => bcrypt('password123'),
            'bio' => 'Web developer',
        ]);
    }
}
