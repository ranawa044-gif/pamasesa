<?php
require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ADMIN ===\n";
$admins = \App\Models\User::where('role', 'ADMIN')->get();
$admins->each(fn($u) => echo "{$u->name} | {$u->email}\n");

echo "\n=== DOSEN (8) ===\n";
$dosenCount = \App\Models\Lecturer::count();
echo "Total: {$dosenCount} dosen\n";
\App\Models\Lecturer::with('user')->get()->each(fn($l) => echo "{$l->nama} | {$l->bidang_keahlian} | {$l->user->email}\n");

echo "\n=== MAHASISWA (30) ===\n";
$studentCount = \App\Models\Student::count();
echo "Total: {$studentCount} mahasiswa\n";

echo "\n=== FINAL PROJECT ===\n";
echo "Total: " . \App\Models\FinalProject::count() . " projek\n";
echo "- APPROVED: " . \App\Models\FinalProject::where('status', 'APPROVED')->count() . "\n";
echo "- SUBMITTED: " . \App\Models\FinalProject::where('status', 'SUBMITTED')->count() . "\n";
echo "- REVISION: " . \App\Models\FinalProject::where('status', 'REVISION')->count() . "\n";

echo "\n=== SUPERVISOR ===\n";
echo "Total: " . \App\Models\Supervisor::count() . " supervisor assignments\n";

echo "\n=== PROGRESS LOG ===\n";
echo "Total: " . \App\Models\ProgressLog::count() . " progress logs\n";

echo "\n========== LOGIN CREDENTIALS ==========\n";
echo "Admin:      admin@pamasesa.test / password\n";
echo "Dosen 1:    dosen1@pamasesa.test / password\n";
echo "Mahasiswa 1: mhs1@pamasesa.test / password\n";
echo "========================================\n";
