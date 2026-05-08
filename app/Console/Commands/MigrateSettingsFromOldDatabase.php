<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MigrateSettingsFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:settings
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate settings (identitas & logo) from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');

        $this->info('===========================================');
        $this->info('  Migrasi Settings dari Database Lama');
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

        // Check if old tables exist
        $identitasExists = DB::connection($connection)->getSchemaBuilder()->hasTable('identitas');
        $logoExists = DB::connection($connection)->getSchemaBuilder()->hasTable('logo');

        if (!$identitasExists && !$logoExists) {
            $this->warn('⚠ Tabel "identitas" dan "logo" tidak ditemukan di database lama.');
            $this->info('Membuat setting default...');

            Setting::firstOrCreate(
                ['id' => 1],
                ['site_name' => 'Portal Kemenag Nganjuk']
            );

            $this->info('✓ Setting default berhasil dibuat');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Memulai migrasi settings...');
        $this->newLine();

        DB::beginTransaction();

        try {
            // Get or create setting record
            $setting = Setting::firstOrCreate(
                ['id' => 1],
                ['site_name' => 'Portal Kemenag Nganjuk']
            );

            // Migrate from identitas table
            if ($identitasExists) {
                $oldIdentitas = DB::connection($connection)->table('identitas')->first();

                if ($oldIdentitas) {
                    $this->line('  • Mengimpor dari tabel identitas...');

                    $setting->site_name = $this->sanitizeString($oldIdentitas->namawebsite ?? $setting->site_name);
                    $setting->site_url = $this->sanitizeString($oldIdentitas->url ?? null);
                    $setting->email = $this->sanitizeString($oldIdentitas->email ?? null);
                    $setting->phone = $this->sanitizeString($oldIdentitas->notelp ?? null);
                    $setting->meta_description = $this->sanitizeString($oldIdentitas->metadeskripsi ?? null);
                    $setting->meta_keywords = $this->sanitizeString($oldIdentitas->metakeyword ?? null);
                    $setting->facebook_url = $this->sanitizeString($oldIdentitas->facebook ?? null);

                    // Handle maps - could be in various columns
                    $maps = $this->sanitizeString($oldIdentitas->maps ?? null);
                    if ($maps) {
                        $setting->maps_embed = $maps;
                    }

                    // Footer description
                    $footerDesc = $this->sanitizeString($oldIdentitas->keterangan ?? null);
                    if ($footerDesc) {
                        $setting->footer_description = $footerDesc;
                    }

                    $setting->save();
                    $this->info('    ✓ Data identitas berhasil diimpor');
                }
            }

            // Migrate from logo table
            if ($logoExists) {
                $oldLogo = DB::connection($connection)->table('logo')->first();

                if ($oldLogo) {
                    $this->line('  • Mengimpor dari tabel logo...');

                    // Logo could be stored in different columns
                    $logoPath = $this->sanitizeString($oldLogo->gambar ?? $oldLogo->logo ?? null);
                    if ($logoPath) {
                        $setting->logo = $logoPath;
                    }

                    // Favicon
                    $favicon = $this->sanitizeString($oldIdentitas->favicon ?? $oldLogo->favicon ?? null);
                    if ($favicon) {
                        $setting->favicon = $favicon;
                    }

                    $setting->save();
                    $this->info('    ✓ Data logo berhasil diimpor');
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine();
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Settings Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Settings');
        $this->info('===========================================');

        $setting = Setting::first();
        $this->line("  Site Name: {$setting->site_name}");
        $this->line("  Site URL: " . ($setting->site_url ?? '-'));
        $this->line("  Email: " . ($setting->email ?? '-'));
        $this->line("  Phone: " . ($setting->phone ?? '-'));
        $this->line("  Logo: " . ($setting->logo ?? '-'));
        $this->line("  Favicon: " . ($setting->favicon ?? '-'));

        $this->newLine();
        $this->info('✓ Migrasi settings selesai!');
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
