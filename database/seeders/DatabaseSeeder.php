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
        // Seed an initial admin if none exists
        if (!User::where('email', 'admin@pcm.local')->exists()) {
            User::create([
                'name' => 'System Administrator',
                'email' => 'admin@pcm.local',
                'password' => bcrypt('password'), // change after first login
                'role' => User::ROLE_ADMIN,
            ]);
        }
    }
}
