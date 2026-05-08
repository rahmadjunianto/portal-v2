<?php

namespace App\Console\Commands;

use App\Models\ExternalLink;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateExternalLinksFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:external-links
                            {--fresh : Drop all existing external links before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate external links from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Link Terkait dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('link_terkait');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "link_terkait" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua link terkait yang sudah dimigrasikan...');
            ExternalLink::truncate();
            $this->info('✓ Semua link terkait berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi link terkait...');
        $this->newLine();

        $oldLinks = DB::connection($connection)->table('link_terkait')->get();
        $total = $oldLinks->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldLinks as $row) {
                $legacyId = $row->id_link_terkait ?? null;
                $title = $this->sanitizeString($row->judul_menu ?? '');
                $description = $this->sanitizeString($row->detail_menu ?? '');
                $url = $this->sanitizeString($row->link ?? '');
                $aktif = $row->aktif ?? 'Y';
                $urutan = $row->urutan ?? 0;

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Link tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($title)) {
                    $errors[] = "Link ID {$legacyId} tidak memiliki judul, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($url)) {
                    $errors[] = "Link ID {$legacyId} tidak memiliki URL, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Validate URL format
                if (!filter_var($url, FILTER_VALIDATE_URL)) {
                    // Try to add http:// if missing
                    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $url = 'https://' . ltrim($url, '/');
                    }
                }

                // Try to extract category from URL or use default
                $category = $this->extractCategory($url);

                $data = [
                    'title' => $title,
                    'description' => $description ?: null,
                    'url' => $url,
                    'category' => $category,
                    'is_active' => $aktif === 'Y',
                    'sort_order' => (int) $urutan,
                ];

                $link = ExternalLink::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($link->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created external link: {$title}");
                } else {
                    $updated++;
                    Log::info("Updated external link: {$title}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi External Links Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Link Terkait');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Total link terkait sekarang: " . ExternalLink::count());

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
        $this->info('✓ Migrasi link terkait selesai!');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Extract category from URL domain.
     */
    private function extractCategory(string $url): ?string
    {
        $parsed = parse_url($url);
        $host = $parsed['host'] ?? '';

        if (empty($host)) {
            return 'other';
        }

        // Remove www prefix
        $host = preg_replace('/^www\./', '', $host);

        // Map common domains to categories
        $categoryMap = [
            'kemenag.go.id' => 'kemenag',
            'Kemenag' => 'kemenag',
            'gov.id' => 'government',
            '.go.id' => 'government',
            'ac.id' => 'education',
            'org' => 'organization',
        ];

        foreach ($categoryMap as $key => $category) {
            if (str_contains($host, $key)) {
                return $category;
            }
        }

        return 'other';
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
