<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComputerLab;
use App\Models\Building;

class ComputerLabs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buildings = Building::whereIn('code', ['MB', 'KS', 'AG', 'MZ', 'SB'])->get()->keyBy('code');

        // Minimal set: one lab on 1st and 3rd floor per building
        $labCodesPerBuilding = ['1F01', '3F01'];

        foreach ($buildings as $bCode => $building) {
            foreach ($labCodesPerBuilding as $labCode) {
                $code = strtoupper($bCode.'-'.$labCode); // ensure global uniqueness
                ComputerLab::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => 'Lab '.$labCode,
                        'code' => $code,
                        'building_id' => $building?->id,
                        'location' => $building->name.' '.$labCode,
                        'capacity' => 30,
                        'description' => 'Computer lab '.$labCode.' in '.$building->name,
                    ]
                );
            }
        }
    }
}
