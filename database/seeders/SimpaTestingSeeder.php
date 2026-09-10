<?php

namespace Database\Seeders;

use App\Models\FinalProject;
use App\Models\Lecturer;
use App\Models\ProgressLog;
use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class SimpaTestingSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@pamasesa.test'],
            ['name' => 'Koordinator Proyek Akhir', 'password' => Hash::make('password'), 'role' => 'ADMIN']
        );

        // Lecturers
        $lecturers = [
            ['nama' => 'Dosen RPL', 'bidang' => 'Rekayasa Perangkat Lunak'],
            ['nama' => 'Dosen Database', 'bidang' => 'Basis Data'],
            ['nama' => 'Dosen IoT', 'bidang' => 'Internet of Things'],
            ['nama' => 'Dosen SPK', 'bidang' => 'Sistem Pendukung Keputusan'],
            ['nama' => 'Dosen AI', 'bidang' => 'Artificial Intelligence'],
            ['nama' => 'Dosen Jaringan', 'bidang' => 'Computer Network'],
            ['nama' => 'Dosen Mobile', 'bidang' => 'Mobile Programming'],
            ['nama' => 'Dosen UI UX', 'bidang' => 'User Interface Design'],
        ];

        foreach ($lecturers as $i => $data) {
            $idx = $i + 1;
            $email = "dosen{$idx}@pamasesa.test";
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $data['nama'], 'password' => Hash::make('password'), 'role' => 'DOSEN']
            );

            Lecturer::firstOrCreate(
                ['user_id' => $user->id],
                ['nidn' => 'NIDN'.str_pad((string)$idx,4,'0',STR_PAD_LEFT), 'nama' => $data['nama'], 'bidang_keahlian' => $data['bidang']]
            );
        }

        // Students and Final Projects
        $developmentMethods = ['Waterfall', 'Prototype', 'Agile Scrum'];
        $additionalMethods = ['Blackbox Testing', 'SAW', 'SUS', 'TOPSIS', 'IoT Sensor'];
        $technologies = ['Laravel MySQL', 'Flutter Firebase', 'React Laravel API'];

        $statuses = array_merge(array_fill(0,15,'APPROVED'), array_fill(0,10,'SUBMITTED'), array_fill(0,5,'REVISION'));

        for ($i = 1; $i <= 30; $i++) {
            $email = "mhs{$i}@pamasesa.test";
            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => "Mahasiswa {$i}", 'password' => Hash::make('password'), 'role' => 'MAHASISWA']
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nim' => str_pad((string)(220000 + $i), 6, '0', STR_PAD_LEFT),
                    'nama' => "Mahasiswa {$i}",
                    'angkatan' => 2022,
                    'kelas' => 'SI-A',
                ]
            );

            // Title variations
            $cat = ($i % 5);
            switch ($cat) {
                case 1:
                    $title = 'Sistem Informasi Inventaris Barang Berbasis Web';
                    break;
                case 2:
                    $title = 'SPK Pemilihan Karyawan Terbaik Menggunakan SAW';
                    break;
                case 3:
                    $title = 'Sistem Monitoring Suhu Berbasis IoT';
                    break;
                case 4:
                    $title = 'Aplikasi Absensi Mobile Berbasis QR Code';
                    break;
                default:
                    $title = 'Perancangan dan Implementasi Sistem Layanan Digital';
                    break;
            }

            $status = array_shift($statuses);

            $fp = FinalProject::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'title' => $title . ' - ' . $student->nama,
                    'background' => 'Latar belakang untuk ' . $title,
                    'research_object' => 'Tujuan penelitian untuk ' . $title,
                    'business_process' => 'Proses bisnis sederhana terkait ' . $title,
                    'system_type' => 'Web Application',
                    'main_features' => 'Fitur utama sistem',
                    'actor_count' => 3,
                    'development_method' => Arr::random($developmentMethods),
                    'additional_method' => Arr::random($additionalMethods),
                    'technology' => Arr::random($technologies),
                    'testing_plan' => 'Rencana testing komprehensif',
                    'status' => $status,
                    'declaration' => true,
                ]
            );
        }

        // Assign supervisors for APPROVED projects evenly
        $approved = FinalProject::where('status', 'APPROVED')->get();
        $allLecturers = Lecturer::all()->values();
        $lecCount = $allLecturers->count();
        $idx = 0;
        foreach ($approved as $fp) {
            // pick two distinct lecturers in rotation
            $first = $allLecturers[$idx % $lecCount];
            $second = $allLecturers[($idx + 1) % $lecCount];

            Supervisor::firstOrCreate(
                ['final_project_id' => $fp->id, 'type' => 'PEMBIMBING_1'],
                ['lecturer_id' => $first->id, 'assigned_at' => Carbon::now()]
            );

            Supervisor::firstOrCreate(
                ['final_project_id' => $fp->id, 'type' => 'PEMBIMBING_2'],
                ['lecturer_id' => $second->id, 'assigned_at' => Carbon::now()]
            );

            $idx += 2;
        }

        // Progress logs for APPROVED projects
        $progressTypes = ['PROPOSAL', 'DESIGN', 'IMPLEMENTATION', 'TESTING'];
        $statusesProgress = ['WAITING', 'APPROVED', 'REVISION'];

        foreach ($approved as $fp) {
            foreach ($progressTypes as $ptype) {
                ProgressLog::create([
                    'final_project_id' => $fp->id,
                    'progress_type' => $ptype,
                    'percentage' => rand(10, 100),
                    'description' => "Progress pengerjaan {$ptype} untuk {$fp->title}",
                    'status' => Arr::random($statusesProgress),
                ]);
            }
        }
    }
}
