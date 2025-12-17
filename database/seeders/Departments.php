<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;


class Departments extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Computer Science',
                'code' => 'CS',
                'location' => 'Agaciro',
                'description' => 'Department of Computer Science',
            ],
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'location' => 'Muhazi',
                'description' => 'Department of Information Technology',
            ],
            [
                'name' => 'Network & Telecommunications',
                'code' => 'NT',
                'location' => 'Sabyinyo',
                'description' => 'Department of Networking and Telecommunications',
            ],
            [
                'name' => 'Electrical Engineering',
                'code' => 'EE',
                'location' => 'Kalisimbi',
                'description' => 'Department of Electrical Engineering',
            ],
            [
                'name' => 'Software Engineering',
                'code' => 'SE',
                'location' => 'Muhabura',
                'description' => 'Department of Software Engineering',
            ],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }
    }
}
