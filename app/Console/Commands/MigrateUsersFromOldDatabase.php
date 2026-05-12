<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigrateUsersFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:users
                            {--fresh : Drop all existing migrated users before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate users from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Users dari Database Lama');
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

        // Fresh option - disable foreign key checks for fresh migration
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua user yang sudah dimigrasikan...');

            // Disable foreign key checks temporarily
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            User::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $this->info('✓ Semua user berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi users...');
        $this->newLine();

        $oldUsers = DB::connection($connection)->table('users')->get();
        $total = $oldUsers->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldUsers as $row) {
                // Handle encoding dari latin1 ke utf8mb4
                $username = $this->sanitizeString($row->username ?? '');
                $name = $this->sanitizeString($row->namalengkap ?? '');
                $email = $this->sanitizeString($row->email ?? '');
                $phone = $this->sanitizeString($row->notelp ?? '');
                $photo = $this->sanitizeString($row->foto ?? '');
                $level = $this->sanitizeString($row->level ?? 'user');
                $blokir = $row->blokir ?? 'N';

                // Validasi username tidak kosong
                if (empty($username)) {
                    $errors[] = "User ID {$row->id} tidak memiliki username, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Cek apakah user sudah ada
                $existingUser = User::where('username', $username)->first();

                // Handle duplicate email - jika email sudah ada di user lain, buat unik
                $uniqueEmail = $email;
                if ($email) {
                    $existingEmail = User::where('email', $email)
                        ->where('username', '!=', $username)
                        ->first();
                    if ($existingEmail) {
                        // Email sudah dipakai user lain, buat unik dengan suffix
                        $uniqueEmail = strtok($email, '@') . '+' . $username . '@placeholder.com';
                        $this->warn("  Email {$email} sudah dipakai, diubah ke {$uniqueEmail} untuk username {$username}");
                    }
                }

                $userData = [
                    'name' => $name ?: 'Unknown User',
                    'email' => $uniqueEmail,
                    'phone' => $phone ?: null,
                    'photo' => $photo ?: null,
                    'password' => bcrypt('1111'), // All migrated users get default password
                    'role_name' => $this->mapRole($level),
                    'is_active' => $blokir === 'N',
                    'legacy_username' => $username,
                ];

                if ($existingUser) {
                    $existingUser->update($userData);
                    $updated++;
                    Log::info("Updated user: {$username}");
                } else {
                    $userData['username'] = $username;
                    User::create($userData);
                    $created++;
                    Log::info("Created user: {$username}");
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Users Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Users');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");

        if (count($errors) > 0) {
            $this->newLine();
            $this->warn('  Errors/Warnings:');
            foreach ($errors as $error) {
                $this->line("    - {$error}");
            }
        }

        $this->newLine();
        $this->info('✓ Migrasi users selesai!');
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

        // Convert to UTF-8 if needed
        $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);

        if ($encoding === 'ISO-8859-1' || $encoding === false) {
            $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        }

        // Trim and clean
        $value = trim($value);
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $value ?: null;
    }

    /**
     * Map old role to new role name.
     */
    private function mapRole(string $level): string
    {
        $level = strtolower(trim($level));

        $roleMap = [
            'admin' => 'admin',
            'moderator' => 'moderator',
            'editor' => 'editor',
            'user' => 'user',
            'penulis' => 'editor',
            'superadmin' => 'admin',
            'super admin' => 'admin',
        ];

        return $roleMap[$level] ?? 'user';
    }
}
