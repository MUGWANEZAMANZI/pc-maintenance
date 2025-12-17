<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ComputerLab;
use App\Models\Department;
use App\Models\Building;

class ComputerLabs extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Choose primary departments for lab assignment
        $cs = Department::where('code', 'CS')->first();
        $it = Department::where('code', 'IT')->first();
        $fallbackDept = Department::first();

        $deptMap = [
            'MB' => $cs ?? $fallbackDept,
            'KS' => $it ?? $fallbackDept,
            'AG' => $cs ?? $fallbackDept,
            'MZ' => $it ?? $fallbackDept,
            'SB' => $cs ?? $fallbackDept,
        ];

        $buildings = Building::whereIn('code', ['MB', 'KS', 'AG', 'MZ', 'SB'])->get()->keyBy('code');

        // Minimal set: one lab on 1st and 3rd floor per building
        $labCodesPerBuilding = ['1F01', '3F01'];

        foreach ($buildings as $bCode => $building) {
            $dept = $deptMap[$bCode] ?? $fallbackDept;
            foreach ($labCodesPerBuilding as $labCode) {
                $code = strtoupper($bCode.'-'.$labCode); // ensure global uniqueness
                ComputerLab::firstOrCreate(
                    ['code' => $code],
                    [
                        'name' => 'Lab '.$labCode,
                        'code' => $code,
                        'department_id' => $dept?->id,
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
