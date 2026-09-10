<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Lab Sistem Informasi 1',
                'location' => 'Gedung SI',
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Lab Sistem Informasi 2',
                'location' => 'Gedung SI',
                'capacity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Sidang Proyek Akhir',
                'location' => 'Gedung Jurusan',
                'capacity' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($rooms as $r) {
            Room::updateOrCreate(
                ['name' => $r['name']],
                $r
            );
        }
    }
}
