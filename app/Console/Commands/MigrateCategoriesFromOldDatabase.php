<?php

namespace App\Console\Commands;

use App\Models\PostCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateCategoriesFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:categories
                            {--fresh : Drop all existing categories before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate categories from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Kategori dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('kategori');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "kategori" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua kategori yang sudah dimigrasikan...');
            // Disable FK check untuk truncate
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
            PostCategory::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info('✓ Semua kategori berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi kategori...');
        $this->newLine();

        $oldCategories = DB::connection($connection)->table('kategori')->get();
        $total = $oldCategories->count();

        // Debug: tampilkan struktur data pertama
        if ($total > 0) {
            $sample = DB::connection($connection)->table('kategori')->first();
            $this->line('  Debug - Sample columns: ' . implode(', ', array_keys((array)$sample)));
            $this->line('  Debug - Sample data: ' . json_encode($sample));
        }

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldCategories as $row) {
                // Nama kolom dengan underscore: id_kategori, nama_kategori, kategori_seo
                $legacyId = $row->id_kategori ?? null;
                $name = $this->sanitizeString($row->nama_kategori ?? '');
                $slug = $this->sanitizeString($row->kategori_seo ?? '');
                $aktif = $row->aktif ?? 'Y';
                $sidebar = $row->sidebar ?? 0;

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Kategori tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($name)) {
                    $errors[] = "Kategori ID {$legacyId} tidak memiliki nama, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Generate slug jika kosong
                if (empty($slug)) {
                    $slug = Str::slug($name);
                }

                // Handle duplicate slug
                $originalSlug = $slug;
                $counter = 1;
                while (PostCategory::where('slug', $slug)->where('legacy_id', '!=', $legacyId)->exists()) {
                    $slug = $originalSlug . '-' . $legacyId;
                }

                $data = [
                    'name' => $name,
                    'slug' => $slug,
                    'is_active' => $aktif === 'Y',
                    'show_in_sidebar' => (int) $sidebar > 0,
                ];

                $category = PostCategory::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($category->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created category: {$name}");
                } else {
                    $updated++;
                    Log::info("Updated category: {$name}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Categories Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Kategori');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Total kategori sekarang: " . PostCategory::count());

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
        $this->info('✓ Migrasi kategori selesai!');
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
}
