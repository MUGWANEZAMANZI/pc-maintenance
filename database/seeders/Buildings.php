<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Building;

class Buildings extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = [
            [
                'name' => 'Muhabura',
                'code' => 'MB',
                'location' => 'Main Campus',
                'description' => 'Muhabura building',
            ],
            [
                'name' => 'Kalisimbi',
                'code' => 'KS',
                'location' => 'Main Campus',
                'description' => 'Kalisimbi building',
            ],
            [
                'name' => 'Agaciro',
                'code' => 'AG',
                'location' => 'Main Campus',
                'description' => 'Agaciro building',
            ],
            [
                'name' => 'Muhazi',
                'code' => 'MZ',
                'location' => 'Main Campus',
                'description' => 'Muhazi building',
            ],
            [
                'name' => 'Sabyinyo',
                'code' => 'SB',
                'location' => 'Main Campus',
                'description' => 'Sabyinyo building',
            ],
        ];

        foreach ($buildings as $bld) {
            Building::firstOrCreate(
                ['code' => $bld['code']],
                $bld
            );
        }
    }
}
