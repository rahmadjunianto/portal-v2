<?php

namespace App\Console\Commands;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateMenusFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:menus
                            {--fresh : Drop all existing menus before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate menus from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Menu dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('menu');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "menu" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua menu yang sudah dimigrasikan...');
            MenuItem::truncate();
            Menu::truncate();
            $this->info('✓ Semua menu berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi menu...');
        $this->newLine();

        // Create default menus
        $headerMenu = Menu::firstOrCreate(
            ['name' => 'Main Menu', 'location' => Menu::LOCATION_HEADER],
            ['name' => 'Main Menu', 'location' => Menu::LOCATION_HEADER]
        );
        $footerMenu = Menu::firstOrCreate(
            ['name' => 'Footer Menu', 'location' => Menu::LOCATION_FOOTER],
            ['name' => 'Footer Menu', 'location' => Menu::LOCATION_FOOTER]
        );

        $this->line("  • Header menu ID: {$headerMenu->id}");
        $this->line("  • Footer menu ID: {$footerMenu->id}");

        $this->newLine();

        // Get all menu items from old database
        $oldMenus = DB::connection($connection)->table('menu')->get();
        $total = $oldMenus->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        // Build mapping for parent items
        $legacyIdMapping = [];

        DB::beginTransaction();

        try {
            // First pass: Create all menu items without parent relationships
            foreach ($oldMenus as $row) {
                $legacyId = $row->id_menu ?? null;
                $namamenu = $this->sanitizeString($row->nama_menu ?? '');
                $link = $this->sanitizeString($row->link ?? '');
                $position = $this->sanitizeString($row->position ?? 'Top');
                $aktif = $row->aktif ?? 'Y';
                $target = $row->target ?? '_self';
                $urutan = $row->urutan ?? 0;

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Menu tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($namamenu)) {
                    $errors[] = "Menu ID {$legacyId} tidak memiliki nama, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Determine which menu based on position
                $menuId = $position === 'Bottom' ? $footerMenu->id : $headerMenu->id;

                // Check if target should open in new tab
                $targetBlank = strtolower($target) === '_blank';

                $data = [
                    'menu_id' => $menuId,
                    'parent_id' => null, // Will be updated in second pass
                    'title' => $namamenu,
                    'url' => $this->normalizeUrl($link),
                    'is_active' => $aktif === 'Y',
                    'target_blank' => $targetBlank,
                    'sort_order' => (int) $urutan,
                ];

                $menuItem = MenuItem::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                // Store mapping of legacy_id to new id
                $legacyIdMapping[$legacyId] = $menuItem->id;

                if ($menuItem->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created menu item: {$namamenu}");
                } else {
                    $updated++;
                    Log::info("Updated menu item: {$namamenu}");
                }

                $progressBar->advance();
            }

            // Second pass: Update parent relationships
            $parentUpdates = 0;
            foreach ($oldMenus as $row) {
                $legacyId = $row->id_menu ?? null;
                $idparent = $row->id_parent ?? null;

                if ($legacyId && $idparent && isset($legacyIdMapping[$legacyId]) && isset($legacyIdMapping[$idparent])) {
                    $menuItem = MenuItem::find($legacyIdMapping[$legacyId]);
                    if ($menuItem && $menuItem->parent_id === null) {
                        $menuItem->update(['parent_id' => $legacyIdMapping[$idparent]]);
                        $parentUpdates++;
                    }
                }
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Menus Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Menu');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Parent relationships updated: {$parentUpdates}");
        $this->line("  Total menu sekarang: " . Menu::count());
        $this->line("  Total menu items sekarang: " . MenuItem::count());

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
        $this->warn('⚠ Catatan: Struktur menu lama mungkin perlu di-review manual');
        $this->warn('   karena navigasi website redesign biasanya berubah.');
        $this->newLine();
        $this->info('✓ Migrasi menu selesai!');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Normalize URL - ensure proper format.
     */
    private function normalizeUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // Skip javascript and special links
        if (str_starts_with($url, 'javascript:') || str_starts_with($url, '#')) {
            return null;
        }

        // Add https if no protocol
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://') && !str_starts_with($url, '/')) {
            $url = '/' . $url;
        }

        return $url;
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
