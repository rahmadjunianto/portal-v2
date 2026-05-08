<?php

namespace App\Console\Commands;

use App\Models\Download;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateDownloadsFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:downloads
                            {--fresh : Drop all existing downloads before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate downloads from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Download dari Database Lama');
        $this->info('===========================================');
        $this->newLine();

        // Check connection
        try {
            DB::connection($connection)->getPdo();
            $this->info("✓ Koneksi ke database lama ({$connection}) berhasil");
        } catch (\Exception $e) {
            $this->error("✗ Gagal terhubung ke database lama: " . $e->getMessage());
            return self::FAILURE;
        }

        // Check if old table exists
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('download');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "download" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua download yang sudah dimigrasikan...');
            Download::truncate();
            $this->info('✓ Semua download berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi download...');
        $this->newLine();

        $oldDownloads = DB::connection($connection)->table('download')->get();
        $total = $oldDownloads->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldDownloads as $row) {
                $legacyId = $row->id_download ?? null;
                $title = $this->sanitizeString($row->judul ?? '');
                $fileName = $this->sanitizeString($row->nama_file ?? '');
                $tglposting = $row->tgl_posting ?? null;
                $hits = $row->hits ?? 0;

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Download tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($title)) {
                    $errors[] = "Download ID {$legacyId} tidak memiliki judul, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($fileName)) {
                    $errors[] = "Download ID {$legacyId} tidak memiliki nama file, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Generate slug from title
                $slug = Str::slug($title . '-' . $legacyId);

                // Handle duplicate slug
                $originalSlug = $slug;
                while (Download::where('slug', $slug)->where('legacy_id', '!=', $legacyId)->exists()) {
                    $slug = $originalSlug . '-' . $legacyId;
                }

                // Determine file type from extension
                $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $fileType = $this->mapFileType($extension);

                // Determine file path (relative path stored in database)
                $filePath = 'downloads/' . $fileName;

                // Parse date
                $publishedAt = $this->parseDateTime($tglposting);

                $data = [
                    'title' => $title,
                    'slug' => $slug,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => null, // File size will be calculated when file is copied
                    'downloads_count' => (int) $hits,
                    'published_at' => $publishedAt,
                ];

                $download = Download::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($download->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created download: {$title}");
                } else {
                    $updated++;
                    Log::info("Updated download: {$title}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Downloads Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Download');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Total download sekarang: " . Download::count());

        if (count($errors) > 0) {
            $this->newLine();
            $this->warn('  Errors/Warnings:');
            foreach (array_slice($errors, 0, 5) as $error) {
                $this->line("    - {$error}");
            }
            if (count($errors) > 5) {
                $this->line("    ... dan " . (count($errors) - 5) . " error lainnya");
            }
        }

        $this->newLine();
        $this->info('⚠ Catatan: File fisik perlu disalin manual ke storage/app/public/downloads/');
        $this->newLine();
        $this->info('✓ Migrasi download selesai!');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Map file extension to file type category.
     */
    private function mapFileType(string $extension): string
    {
        $typeMap = [
            'pdf' => 'document',
            'doc' => 'document',
            'docx' => 'document',
            'xls' => 'spreadsheet',
            'xlsx' => 'spreadsheet',
            'csv' => 'spreadsheet',
            'ppt' => 'presentation',
            'pptx' => 'presentation',
            'zip' => 'archive',
            'rar' => 'archive',
            '7z' => 'archive',
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'gif' => 'image',
            'mp4' => 'video',
            'avi' => 'video',
            'mp3' => 'audio',
        ];

        return $typeMap[$extension] ?? 'other';
    }

    /**
     * Parse datetime string.
     */
    private function parseDateTime(?string $datetime): ?string
    {
        if (empty($datetime)) {
            return null;
        }

        // Already in correct format
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $datetime)) {
            return $datetime;
        }

        // Try strtotime
        $timestamp = strtotime($datetime);
        if ($timestamp) {
            return date('Y-m-d H:i:s', $timestamp);
        }

        return null;
    }

    /**
     * Sanitize string from old database encoding.
     */
    private function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);

        if ($encoding === 'ISO-8859-1' || $encoding === false) {
            $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        }

        $value = trim($value);

        return $value ?: null;
    }
}
