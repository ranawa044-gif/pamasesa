<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * Halaman ringkasan status database & panel backup
     */
    public function index(): View
    {
        $dbName = DB::connection()->getDatabaseName();
        $pdo = DB::connection()->getPdo();
        $mysqlVersion = $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);

        // Ambil data ukuran tabel dari information_schema
        $tablesMeta = DB::select('
            SELECT 
                table_name AS name,
                engine AS engine,
                ROUND((data_length / 1024), 2) AS data_kb,
                ROUND((index_length / 1024), 2) AS index_kb,
                ROUND(((data_length + index_length) / 1024), 2) AS total_kb,
                table_collation AS collation
            FROM information_schema.TABLES 
            WHERE table_schema = ? AND table_type = "BASE TABLE"
            ORDER BY table_name ASC
        ', [$dbName]);

        $tables = [];
        $totalRows = 0;
        $totalSizeKb = 0;

        foreach ($tablesMeta as $meta) {
            $rowCount = DB::table($meta->name)->count();
            $totalRows += $rowCount;
            $totalSizeKb += (float) $meta->total_kb;

            $tables[] = [
                'name' => $meta->name,
                'engine' => $meta->engine ?? 'InnoDB',
                'rows' => $rowCount,
                'data_kb' => (float) $meta->data_kb,
                'index_kb' => (float) $meta->index_kb,
                'total_kb' => (float) $meta->total_kb,
                'collation' => $meta->collation ?? 'utf8mb4_unicode_ci',
            ];
        }

        $totalSizeMb = round($totalSizeKb / 1024, 2);

        $dbInfo = [
            'name' => $dbName,
            'host' => config('database.connections.mysql.host', '127.0.0.1'),
            'port' => config('database.connections.mysql.port', '3306'),
            'version' => $mysqlVersion,
            'table_count' => count($tables),
            'total_rows' => $totalRows,
            'total_size_kb' => round($totalSizeKb, 2),
            'total_size_mb' => $totalSizeMb,
        ];

        return view('admin.backup.index', compact('dbInfo', 'tables'));
    }

    /**
     * Download dump database dalam format file .sql secara streaming
     */
    public function download(Request $request): StreamedResponse
    {
        $dbName = DB::connection()->getDatabaseName();
        $timestamp = date('Y-m-d_His');
        $filename = "backup_pamasesa_{$dbName}_{$timestamp}.sql";

        return response()->streamDownload(function () use ($dbName) {
            // Naikkan batas waktu eksekusi untuk database besar
            if (!ini_get('safe_mode')) {
                set_time_limit(300);
            }

            $pdo = DB::connection()->getPdo();
            $output = fopen('php://output', 'w');

            // Header Komentar SQL
            fwrite($output, "-- ========================================================\n");
            fwrite($output, "-- PAMASESA DATABASE BACKUP\n");
            fwrite($output, "-- Sistem Manajemen Proyek Akhir D3 Sistem Informasi\n");
            fwrite($output, "-- Database   : {$dbName}\n");
            fwrite($output, "-- Host       : " . config('database.connections.mysql.host') . "\n");
            fwrite($output, "-- Generated  : " . date('Y-m-d H:i:s') . "\n");
            fwrite($output, "-- Server ver : " . $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION) . "\n");
            fwrite($output, "-- PHP ver    : " . PHP_VERSION . "\n");
            fwrite($output, "-- ========================================================\n\n");

            fwrite($output, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($output, "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n");
            fwrite($output, "SET time_zone = '+00:00';\n");
            fwrite($output, "SET NAMES utf8mb4;\n");
            fwrite($output, "START TRANSACTION;\n\n");

            // Ambil daftar seluruh tabel
            $tables = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
            $tableKey = "Tables_in_" . $dbName;

            foreach ($tables as $t) {
                $tableName = $t->$tableKey;

                fwrite($output, "-- --------------------------------------------------------\n");
                fwrite($output, "-- Struktur tabel untuk `{$tableName}`\n");
                fwrite($output, "-- --------------------------------------------------------\n\n");
                fwrite($output, "DROP TABLE IF EXISTS `{$tableName}`;\n");

                $createTableRes = DB::select("SHOW CREATE TABLE `{$tableName}`");
                if (!empty($createTableRes)) {
                    $ddl = $createTableRes[0]->{'Create Table'} ?? null;
                    if ($ddl) {
                        fwrite($output, $ddl . ";\n\n");
                    }
                }

                // Dump Data tabel
                $rowCount = DB::table($tableName)->count();
                if ($rowCount > 0) {
                    fwrite($output, "-- Dumping data untuk tabel `{$tableName}` ({$rowCount} baris)\n");
                    fwrite($output, "LOCK TABLES `{$tableName}` WRITE;\n");

                    DB::table($tableName)->orderBy(DB::raw('1'))->chunk(200, function ($rows) use ($output, $tableName, $pdo) {
                        foreach ($rows as $row) {
                            $rowArr = (array) $row;
                            $columns = array_map(fn($c) => "`" . str_replace('`', '``', $c) . "`", array_keys($rowArr));
                            $values = array_map(function ($val) use ($pdo) {
                                if (is_null($val)) {
                                    return 'NULL';
                                }
                                if (is_int($val) || is_float($val)) {
                                    return (string) $val;
                                }
                                return $pdo->quote($val);
                            }, array_values($rowArr));

                            fwrite($output, "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n");
                        }
                    });

                    fwrite($output, "UNLOCK TABLES;\n\n");
                }
            }

            fwrite($output, "COMMIT;\n");
            fwrite($output, "SET FOREIGN_KEY_CHECKS=1;\n\n");
            fwrite($output, "-- ========================================================\n");
            fwrite($output, "-- Backup selesai pada " . date('Y-m-d H:i:s') . "\n");
            fwrite($output, "-- ========================================================\n");

            fclose($output);
        }, $filename, [
            'Content-Type' => 'application/sql; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
