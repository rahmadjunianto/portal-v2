<?php

namespace App\Console\Commands;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateAgendasFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:agendas
                            {--fresh : Drop all existing agendas before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate agendas from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Agenda dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('agenda');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "agenda" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua agenda yang sudah dimigrasikan...');
            Agenda::truncate();
            $this->info('✓ Semua agenda berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi agenda...');
        $this->newLine();

        // Cache user mapping
        $this->info('  • Membuat cache mapping users...');
        $userMapping = $this->buildUserMapping($connection);
        $this->info('    ✓ ' . count($userMapping) . ' user di-cache');

        $this->newLine();

        $oldAgendas = DB::connection($connection)->table('agenda')->get();
        $total = $oldAgendas->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldAgendas as $row) {
                $legacyId = $row->id_agenda ?? null;
                $title = $this->sanitizeString($row->tema ?? '');
                $slug = $this->sanitizeString($row->tema_seo ?? '');
                $description = $this->sanitizeContent($row->isi_agenda ?? '');
                $location = $this->sanitizeString($row->tempat ?? '');
                $senderName = $this->sanitizeString($row->pengirim ?? '');
                $image = $this->sanitizeString($row->gambar ?? '');
                $tglmulai = $row->tgl_mulai ?? null;
                $tglselesai = $row->tgl_selesai ?? null;
                $dibaca = $row->dibaca ?? 0;
                $username = $this->sanitizeString($row->username ?? '');
                $tglposting = $row->tgl_posting ?? null;
                $jam = $row->jam ?? '00:00:00';

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Agenda tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($title)) {
                    $errors[] = "Agenda ID {$legacyId} tidak memiliki tema, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Get author ID from cache
                $authorId = $userMapping[$username] ?? null;

                // Generate slug jika kosong
                if (empty($slug)) {
                    $slug = Str::slug($title . '-' . $legacyId);
                }

                // Handle duplicate slug
                $originalSlug = $slug;
                while (Agenda::where('slug', $slug)->where('legacy_id', '!=', $legacyId)->exists()) {
                    $slug = $originalSlug . '-' . $legacyId;
                }

                // Parse dates
                $startDate = $this->parseDate($tglmulai);
                $endDate = $this->parseDate($tglselesai);

                // Combine date and time for published_at
                $publishedAt = null;
                if ($tglposting) {
                    // Validate jam is a proper time format
                    $isValidTime = $jam && preg_match('/^\d{2}:\d{2}(:\d{2})?$/', trim($jam));
                    $timeToUse = $isValidTime ? $jam : '00:00:00';
                    $publishedAt = $this->combineDateTime($tglposting, $timeToUse);
                }

                // Store raw jam as event_time_text if it's not a valid time format
                $rawJam = $row->jam ?? null;
                $eventTimeText = null;
                if ($rawJam && !preg_match('/^\d{2}:\d{2}(:\d{2})?$/', trim($rawJam))) {
                    $eventTimeText = $this->sanitizeString($rawJam);
                }

                $data = [
                    'author_id' => $authorId,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => $description,
                    'location' => $location ?: null,
                    'sender_name' => $senderName ?: null,
                    'image' => $image ?: null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'event_time_text' => $eventTimeText,
                    'views' => (int) $dibaca,
                    'published_at' => $publishedAt,
                ];

                $agenda = Agenda::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($agenda->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created agenda: {$title}");
                } else {
                    $updated++;
                    Log::info("Updated agenda: {$title}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Agendas Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Agenda');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Total agenda sekarang: " . Agenda::count());

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
        $this->info('✓ Migrasi agenda selesai!');
        $this->newLine();

        return self::SUCCESS;
    }

    /**
     * Build user mapping from old database.
     */
    private function buildUserMapping(string $connection): array
    {
        $mapping = [];
        $users = DB::connection($connection)->table('users')->get();

        foreach ($users as $user) {
            $username = $this->sanitizeString($user->username ?? '');
            if (!empty($username)) {
                $newUser = User::where('username', $username)->first();
                if ($newUser) {
                    $mapping[$username] = $newUser->id;
                }
            }
        }

        return $mapping;
    }

    /**
     * Parse date string to Y-m-d format.
     */
    private function parseDate(?string $date): ?string
    {
        if (empty($date)) {
            return null;
        }

        // Already in correct format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // Handle date ranges like "2016-10-11 09:00 s/d Selesai"
        // Extract just the date part before any text
        $cleanedDate = preg_replace('/^(.+?)(?:\s+s\/d|\s+s\/d\s+selesai).*$/i', '$1', $date);

        // If still same as input, try to extract date pattern
        if ($cleanedDate === $date) {
            // Extract first date pattern YYYY-MM-DD or similar
            if (preg_match('/(\d{4}-\d{2}-\d{2})/', $date, $matches)) {
                $cleanedDate = $matches[1];
            } elseif (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $date, $matches)) {
                // Handle DD/MM/YYYY format
                $parts = explode('/', $matches[1]);
                $cleanedDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            }
        }

        // Try strtotime
        $timestamp = strtotime($cleanedDate);
        if ($timestamp) {
            return date('Y-m-d', $timestamp);
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
            $date = trim($date);
            $time = trim($time);

            // Handle problematic date formats like "2016-10-11 09:00 s/d Selesai"
            // Extract just the date part if time is included
            if (preg_match('/^(.{10})/', $date, $matches)) {
                $date = $matches[1];
            } elseif (preg_match('/(\d{4}-\d{2}-\d{2})/', $date, $matches)) {
                $date = $matches[1];
            }

            if (empty($time) || $time === '00:00:00') {
                $time = '00:00:00';
            }

            $datetime = $date . ' ' . $time;

            // Validate format
            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $datetime)) {
                return $datetime;
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
