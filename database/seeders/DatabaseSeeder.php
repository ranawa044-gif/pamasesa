<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pamasesa.local'],
            ['name' => 'Koordinator PA', 'password' => Hash::make('password'), 'role' => 'ADMIN']
        );

        $studentUser = User::firstOrCreate(
            ['email' => 'mahasiswa@pamasesa.local'],
            ['name' => 'Mahasiswa Demo', 'password' => Hash::make('password'), 'role' => 'MAHASISWA']
        );

        Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'nim' => '230001',
                'nama' => 'Mahasiswa Demo',
                'angkatan' => 2023,
                'kelas' => 'SI-6A',
                'phone' => '081234567890',
                'guardian_phone' => '081298765432',
            ]
        );

        $lecturerUser = User::firstOrCreate(
            ['email' => 'dosen@pamasesa.local'],
            ['name' => 'Dosen Demo', 'password' => Hash::make('password'), 'role' => 'DOSEN']
        );

        Lecturer::firstOrCreate(
            ['user_id' => $lecturerUser->id],
            ['nidn' => '0011223344', 'nama' => 'Dosen Demo', 'bidang_keahlian' => 'Sistem Informasi']
        );

        foreach (['Ruang Seminar A', 'Ruang Seminar B', 'Ruang Seminar C'] as $roomName) {
            Room::firstOrCreate(['name' => $roomName]);
        }

        // Ensure our specific test rooms exist
        $this->call(\Database\Seeders\RoomsSeeder::class);

        // Additional testing data
        $this->call(\Database\Seeders\SimpaTestingSeeder::class);

        // Create some seminar proposals ready to be scheduled
        $this->call(\Database\Seeders\SeminarProposalSeeder::class);
    }
}
