<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PC;
use App\Models\Accessory;
use App\Models\NetworkDevice;
use App\Models\ComputerLab;

class EquipmentSeeder extends Seeder
{
    /**
     * Seed 10 PCs, 5 Accessories, and 21 Network Devices.
     */
    public function run(): void
    {
        $labs = ComputerLab::with('building')->get();
        if ($labs->isEmpty()) {
            $this->command?->warn('No labs found. Run Buildings and ComputerLabs seeders first.');
            return;
        }

        $brands = ['Dell', 'HP', 'Lenovo', 'Asus', 'Acer', 'Cisco', 'TP-Link', 'Ubiquiti', 'MikroTik'];
        $health = ['healthy', 'malfunctioning', 'dead'];
        $currentYear = (int) date('Y');

        // Helper to pick a lab and return lab/building pair
        $pickLab = function () use ($labs) {
            $lab = $labs->random();
            return [$lab->id, $lab->building_id];
        };

        // 10 PCs
        for ($i = 1; $i <= 10; $i++) {
            [$labId, $buildingId] = $pickLab();
            PC::firstOrCreate(
                ['device_name' => 'PC'.str_pad((string)$i, 2, '0', STR_PAD_LEFT)],
                [
                    'brand' => $brands[array_rand($brands)],
                    'registration_year' => rand($currentYear - 6, $currentYear),
                    'health' => $health[array_rand($health)],
                    'specifications' => 'Core i5, 8GB RAM, 256GB SSD',
                    'hdd' => '256GB SSD',
                    'ram' => '8GB',
                    'os' => 'Windows 10',
                    'computer_lab_id' => $labId,
                    'building_id' => $buildingId,
                    'technician_id' => null,
                ]
            );
        }

        // 5 Accessories
        $accessoryTypes = ['Mouse', 'Keyboard', 'Headset', 'Projector', 'Printer'];
        for ($i = 1; $i <= 5; $i++) {
            [$labId, $buildingId] = $pickLab();
            Accessory::firstOrCreate(
                ['device_name' => 'ACC'.str_pad((string)$i, 2, '0', STR_PAD_LEFT)],
                [
                    'type' => $accessoryTypes[$i - 1] ?? 'Accessory',
                    'brand' => $brands[array_rand($brands)],
                    'registration_year' => rand($currentYear - 6, $currentYear),
                    'health' => $health[array_rand($health)],
                    'computer_lab_id' => $labId,
                    'building_id' => $buildingId,
                    'technician_id' => null,
                ]
            );
        }

        // 21 Network Devices
        $networkTypes = [
            'Router', 'Switch', 'Access Point', 'Firewall', 'Patch Panel',
            'Repeater', 'Bridge', 'Gateway', 'Modem', 'Controller',
            'Load Balancer', 'IDS', 'IPS', 'Media Converter', 'PoE Injector',
            'Antenna', 'Transceiver', 'SFP Module', 'Cable Tester', 'UPS', 'Fiber Patch Panel',
        ];
        for ($i = 1; $i <= 21; $i++) {
            [$labId, $buildingId] = $pickLab();
            NetworkDevice::firstOrCreate(
                ['device_name' => 'ND'.str_pad((string)$i, 2, '0', STR_PAD_LEFT)],
                [
                    'type' => $networkTypes[$i - 1] ?? 'Network Device',
                    'brand' => $brands[array_rand($brands)],
                    'registration_year' => rand($currentYear - 6, $currentYear),
                    'health' => $health[array_rand($health)],
                    'computer_lab_id' => $labId,
                    'building_id' => $buildingId,
                    'technician_id' => null,
                ]
            );
        }

        $this->command?->info('Seeded: 10 PCs, 5 Accessories, 21 Network Devices.');
    }
}
