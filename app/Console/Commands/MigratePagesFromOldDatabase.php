<?php

namespace App\Console\Commands;

use App\Models\Page;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigratePagesFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pages
                            {--fresh : Drop all existing pages before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate pages from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Halaman Statis dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('halamanstatis');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "halamanstatis" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua halaman yang sudah dimigrasikan...');
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Page::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info('✓ Semua halaman berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi halaman statis...');
        $this->newLine();

        $oldPages = DB::connection($connection)->table('halamanstatis')->get();
        $total = $oldPages->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldPages as $row) {
                // Nama kolom dengan underscore
                $legacyId = $row->id_halaman ?? null;
                $title = $this->sanitizeString($row->judul ?? '');
                $slug = $this->sanitizeString($row->judul_seo ?? '');
                $content = $this->sanitizeContent($row->isi_halaman ?? '');
                $coverImage = $this->sanitizeString($row->gambar ?? '');
                $username = $this->sanitizeString($row->username ?? '');
                $dibaca = $row->dibaca ?? 0;
                $tglposting = $row->tgl_posting ?? null;
                $jam = $row->jam ?? '00:00:00';

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Halaman tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($title)) {
                    $errors[] = "Halaman ID {$legacyId} tidak memiliki judul, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Find author
                $author = null;
                if (!empty($username)) {
                    $author = User::where('username', $username)->first();
                }

                // Generate slug jika kosong
                if (empty($slug)) {
                    $slug = Str::slug($title);
                }

                // Handle duplicate slug
                $originalSlug = $slug;
                $counter = 1;
                while (Page::where('slug', $slug)->where('legacy_id', '!=', $legacyId)->exists()) {
                    $slug = $originalSlug . '-' . $legacyId;
                }

                // Combine date and time
                $publishedAt = null;
                if ($tglposting) {
                    $publishedAt = $this->combineDateTime($tglposting, $jam);
                }

                $data = [
                    'author_id' => $author?->id,
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $content,
                    'cover_image' => $coverImage ?: null,
                    'page_type' => 'statis', // Default type
                    'views' => (int) $dibaca,
                    'published_at' => $publishedAt,
                ];

                $page = Page::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($page->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created page: {$title}");
                } else {
                    $updated++;
                    Log::info("Updated page: {$title}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Pages Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Halaman Statis');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Total halaman sekarang: " . Page::count());

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
        $this->info('✓ Migrasi halaman statis selesai!');
        $this->newLine();

        return self::SUCCESS;
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

    /**
     * Sanitize HTML content from old database.
     */
    private function sanitizeContent(?string $content): string
    {
        if ($content === null) {
            return '';
        }

        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);

        if ($encoding === 'ISO-8859-1' || $encoding === false) {
            $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
        }

        return trim($content);
    }

    /**
     * Combine date and time strings.
     */
    private function combineDateTime(string $date, string $time): ?string
    {
        try {
            // Handle various date formats
            $date = trim($date);
            $time = trim($time);

            if (empty($time) || $time === '00:00:00') {
                $time = '00:00:00';
            }

            // Try to parse the datetime
            $datetime = $date . ' ' . $time;

            // Validate format
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $datetime)) {
                return $datetime;
            }

            // Try strtotime conversion
            $timestamp = strtotime($datetime);
            if ($timestamp) {
                return date('Y-m-d H:i:s', $timestamp);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
