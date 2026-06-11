<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearSitemapCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:clear';

    /**
     * The console command description.
     */
    protected $description = 'Clear the sitemap cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Cache::forget('sitemap_xml');

        $this->info('✓ Sitemap cache cleared successfully!');

        return Command::SUCCESS;
    }
}
