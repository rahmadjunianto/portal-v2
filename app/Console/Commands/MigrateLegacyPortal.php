<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateLegacyPortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:legacy-portal
                            {--fresh : Drop all existing migrated data before migrating}
                            {--skip-users : Skip users migration}
                            {--skip-categories : Skip categories migration}
                            {--skip-settings : Skip settings migration}
                            {--skip-pages : Skip pages migration}
                            {--skip-posts : Skip posts migration}
                            {--skip-agendas : Skip agendas migration}
                            {--skip-downloads : Skip downloads migration}
                            {--skip-links : Skip external links migration}
                            {--skip-menus : Skip menus migration}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all data from legacy portal database to new Laravel database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                                                            ║');
        $this->info('║           LEGACY PORTAL MIGRATION ORCHESTRATOR             ║');
        $this->info('║                    Kemenag Nganjuk                          ║');
        $this->info('║                                                            ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Check if running fresh
        $isFresh = $this->option('fresh');
        if ($isFresh) {
            $this->warn('⚠ Mode FRESH aktif - semua data lama akan dihapus!');
        }

        // Auto-confirm if fresh mode is active
        $autoConfirm = $isFresh || $this->option('yes');

        if (!$autoConfirm) {
            if (!$this->confirm('Lanjutkan migrasi?')) {
                $this->info('Migrasi dibatalkan.');
                return self::SUCCESS;
            }
        } else {
            $this->info('Mode auto-confirm aktif (--fresh atau --yes)');
        }

        $connection = $this->option('connection');
        $this->info("Database connection: {$connection}");
        $this->newLine();

        // Define migration steps in order
        $steps = [
            [
                'name' => 'Users',
                'command' => 'migrate:users',
                'skip' => $this->option('skip-users'),
                'description' => 'Migrasi data pengguna',
            ],
            [
                'name' => 'Settings',
                'command' => 'migrate:settings',
                'skip' => $this->option('skip-settings'),
                'description' => 'Migrasi pengaturan website',
            ],
            [
                'name' => 'Categories',
                'command' => 'migrate:categories',
                'skip' => $this->option('skip-categories'),
                'description' => 'Migrasi kategori berita',
            ],
            [
                'name' => 'Pages',
                'command' => 'migrate:pages',
                'skip' => $this->option('skip-pages'),
                'description' => 'Migrasi halaman statis',
            ],
            [
                'name' => 'Posts',
                'command' => 'migrate:posts',
                'skip' => $this->option('skip-posts'),
                'description' => 'Migrasi berita & tags',
            ],
            [
                'name' => 'Agendas',
                'command' => 'migrate:agendas',
                'skip' => $this->option('skip-agendas'),
                'description' => 'Migrasi agenda',
            ],
            [
                'name' => 'Downloads',
                'command' => 'migrate:downloads',
                'skip' => $this->option('skip-downloads'),
                'description' => 'Migrasi file download',
            ],
            [
                'name' => 'External Links',
                'command' => 'migrate:external-links',
                'skip' => $this->option('skip-links'),
                'description' => 'Migrasi link terkait',
            ],
            [
                'name' => 'Menus',
                'command' => 'migrate:menus',
                'skip' => $this->option('skip-menus'),
                'description' => 'Migrasi struktur menu',
            ],
        ];

        // Filter out skipped steps
        $activeSteps = array_filter($steps, fn($step) => !$step['skip']);

        $this->info('Langkah migrasi yang akan dijalankan:');
        foreach ($activeSteps as $index => $step) {
            $this->line("  " . ($index + 1) . ". {$step['name']} - {$step['description']}");
        }
        $this->newLine();

        if (count($activeSteps) === 0) {
            $this->warn('Tidak ada langkah migrasi yang dipilih.');
            return self::SUCCESS;
        }

        // Final confirmation (skip if auto-confirm is active)
        if (!$autoConfirm) {
            if (!$this->confirm('Mulai migrasi?')) {
                $this->info('Migrasi dibatalkan.');
                return self::SUCCESS;
            }
        }

        $this->newLine();
        $this->info(str_repeat('=', 60));
        $this->newLine();

        // Define commands that support --fresh option
        $commandsWithFreshOption = ['migrate:users', 'migrate:categories', 'migrate:posts', 'migrate:pages', 'migrate:agendas', 'migrate:downloads', 'migrate:external-links', 'migrate:menus'];

        // Run migrations
        $results = [];
        $startTime = microtime(true);

        foreach ($activeSteps as $step) {
            $this->info("▶ Memulai: {$step['name']}");
            $this->line("  {$step['description']}");
            $this->newLine();

            // Build options array for call
            $options = [
                '--connection' => $connection,
            ];

            if ($isFresh && in_array($step['command'], $commandsWithFreshOption)) {
                $options['--fresh'] = true;
            }

            $result = $this->call($step['command'], $options);

            $results[$step['name']] = $result;

            $this->newLine();
            $this->info(str_repeat('-', 60));
            $this->newLine();

            if ($result !== self::SUCCESS) {
                $this->error("✗ Gagal migrasi: {$step['name']}");
                $this->newLine();

                if (!$this->confirm('Lanjutkan ke langkah berikutnya?', true)) {
                    break;
                }
            }
        }

        $totalTime = round(microtime(true) - $startTime, 2);

        // Summary
        $this->newLine();
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                      RINGKASAN MIGRASI                     ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->newLine();

        $successCount = 0;
        $failCount = 0;

        foreach ($results as $name => $result) {
            $status = $result === self::SUCCESS ? '✓ Berhasil' : '✗ Gagal';
            $color = $result === self::SUCCESS ? 'info' : 'error';
            $this->{$color}(sprintf('  %-20s %s', $name, $status));

            if ($result === self::SUCCESS) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $this->newLine();
        $this->line("  Total waktu: {$totalTime} detik");
        $this->line("  Berhasil: {$successCount}");
        $this->line("  Gagal: {$failCount}");

        $this->newLine();

        if ($failCount === 0) {
            $this->info('✓✓✓ SEMUA MIGRASI BERHASIL ✓✓✓');
        } else {
            $this->warn('⚠ Beberapa migrasi gagal. Cek log untuk detail.');
        }

        $this->newLine();
        $this->info('Langkah selanjutnya:');
        $this->line('  1. Review hasil migrasi di database baru');
        $this->line('  2. Salin file media ke storage Laravel');
        $this->line('  3. Generate link symbolic untuk storage');
        $this->line('  4. Test tampilan frontend');
        $this->newLine();

        return $failCount === 0 ? self::SUCCESS : self::FAILURE;
    }
}
