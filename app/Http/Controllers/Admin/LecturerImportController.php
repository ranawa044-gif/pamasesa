<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportLecturersRequest;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Shuchkin\SimpleXLSX;
use Shuchkin\SimpleXLSXGen;

class LecturerImportController extends Controller
{
    public function importForm(): View
    {
        return view('admin.lecturers.import');
    }

    public function downloadTemplate(): \Illuminate\Http\Response
    {
        $rows = [
            ['nidn', 'nama', 'email', 'bidang_keahlian'],
        ];

        $xlsx = SimpleXLSXGen::fromArray($rows, 'Template Dosen');
        $content = (string) $xlsx;

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="lecturer-import-template.xlsx"',
        ]);
    }

    public function import(ImportLecturersRequest $request): RedirectResponse
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
                if (count($row) < 4) {
                    $invalidRows++;
                    $importErrors[] = "Baris {$rowNumber}: kolom kurang dari 4.";
                    continue;
                }

                [$nidn, $nama, $email, $bidangKeahlian] = array_map(fn($value) => trim((string) $value), $row);
                if (! $nidn || ! $nama || ! $email) {
                    $invalidRows++;
                    $importErrors[] = "Baris {$rowNumber}: NIDN, nama, dan email harus diisi.";
                    continue;
                }

                if (User::where('email', $email)->exists() || Lecturer::where('nidn', $nidn)->exists()) {
                    $duplicateRows++;
                    $importErrors[] = "Baris {$rowNumber}: NIDN atau email sudah terdaftar.";
                    continue;
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($nidn),
                    'role' => 'DOSEN',
                ]);

                Lecturer::create([
                    'user_id' => $user->id,
                    'nidn' => $nidn,
                    'nama' => $nama,
                    'bidang_keahlian' => $bidangKeahlian,
                ]);

                $created++;
            }
        });

        return redirect()->route('admin.lecturers.index')->with([
            'success' => "Import dosen selesai. Data baru: {$created}.",
            'import_summary' => [
                'Data baru' => $created,
                'Duplikat diabaikan' => $duplicateRows,
                'Baris tidak valid' => $invalidRows,
            ],
            'import_errors' => array_slice($importErrors, 0, 10),
        ]);
    }
}
