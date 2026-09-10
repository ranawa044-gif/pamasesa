<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStudentsRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class StudentImportController extends Controller
{
    public function importForm(): View
    {
        return view('admin.students.import');
    }

    public function downloadTemplate(): \Illuminate\Http\Response
    {
        $rows = [
            ['nim', 'nama', 'email', 'kelas', 'angkatan'],
        ];

        $xlsx = SimpleXLSXGen::fromArray($rows, 'Template Mahasiswa');
        $content = (string) $xlsx;

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="student-import-template.xlsx"',
        ]);
    }

    public function import(ImportStudentsRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $rows = [];
        if (in_array($extension, ['xlsx', 'xls'], true)) {
            $xlsx = SimpleXLSX::parse($file->getPathname());
            if ($xlsx === false) {
                return redirect()->back()->with('error', 'Format file tidak valid atau rusak.');
            }

            foreach ($xlsx->rows() as $index => $row) {
                if ($index === 0) {
                    continue;
                }
                $rows[] = $row;
            }
        } else {
            $csv = fopen($file->getPathname(), 'r');
            while (($row = fgetcsv($csv)) !== false) {
                $rows[] = $row;
            }
            fclose($csv);
            array_shift($rows);
        }

        $created = 0;
        $duplicateRows = 0;
        $invalidRows = 0;
        $importErrors = [];

        DB::transaction(function () use ($rows, &$created, &$duplicateRows, &$invalidRows, &$importErrors) {
            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;
                if (count($row) < 5) {
                    $invalidRows++;
                    $importErrors[] = "Baris {$rowNumber}: kolom kurang dari 5.";
                    continue;
                }

                [$nim, $nama, $email, $kelas, $angkatan] = array_map(fn($value) => trim((string) $value), $row);
                if (! $nim || ! $nama || ! $email) {
                    $invalidRows++;
                    $importErrors[] = "Baris {$rowNumber}: NIM, nama, dan email harus diisi.";
                    continue;
                }

                if (User::where('email', $email)->exists() || Student::where('nim', $nim)->exists()) {
                    $duplicateRows++;
                    $importErrors[] = "Baris {$rowNumber}: NIM atau email sudah terdaftar.";
                    continue;
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nim),
                    'role' => 'MAHASISWA',
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'nama' => $nama,
                    'angkatan' => $angkatan,
                    'kelas' => $kelas,
                ]);

                $created++;
            }
        });

        return redirect()->route('admin.students.index')->with([
            'success' => "Import mahasiswa selesai. Data baru: {$created}.",
            'import_summary' => [
                'Data baru' => $created,
                'Duplikat diabaikan' => $duplicateRows,
                'Baris tidak valid' => $invalidRows,
            ],
            'import_errors' => array_slice($importErrors, 0, 10),
        ]);
    }
}
