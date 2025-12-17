<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\Buildings;
use Database\Seeders\ComputerLabs;
use Database\Seeders\EquipmentSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core data
        $this->call([
            Buildings::class,
            ComputerLabs::class,
            EquipmentSeeder::class,
        ]);

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
