<?php

namespace App\Console\Commands;

use App\Services\ContentMonitorService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOutdatedContent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'content:check-outdated
                            {--days=180 : Threshold days for outdated content}
                            {--notify : Send notification if outdated content found}
                            {--archive : Archive old agendas}
                            {--json : Output in JSON format}';

    /**
     * The console command description.
     */
    protected $description = 'Check for outdated content and report';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $thresholdDays = (int) $this->option('days');
        $shouldNotify = $this->option('notify');
        $shouldArchive = $this->option('archive');
        $jsonOutput = $this->option('json');

        $this->info("Checking for outdated content (threshold: {$thresholdDays} days)...");

        $monitorService = new ContentMonitorService($thresholdDays);

        // Check for outdated content
        $summary = $monitorService->getSummary();
        $outdated = $monitorService->getOutdatedContent();

        if ($jsonOutput) {
            $this->line(json_encode([
                'summary' => $summary,
                'outdated_content' => $this->formatOutdatedForJson($outdated),
            ], JSON_PRETTY_PRINT));

            return $this->exitCode($summary['total_outdated'] > 0);
        }

        // Display summary
        $this->newLine();
        $this->info('=== SUMMARY ===');
        $this->table(
            ['Type', 'Outdated Count'],
            [
                ['Agendas', $summary['outdated_agendas']],
                ['Posts', $summary['outdated_posts']],
                ['Pages', $summary['outdated_pages']],
                ['Downloads', $summary['outdated_downloads']],
                ['TOTAL', $summary['total_outdated']],
            ]
        );

        // Display detailed outdated content
        $this->displayOutdatedDetails($outdated);

        // Archive old agendas if requested
        if ($shouldArchive) {
            $archivedCount = $monitorService->archiveOldAgendas();
            $this->info("Archived {$archivedCount} old agendas.");
        }

        // Log the check
        Log::info('Content outdated check completed', $summary);

        // Exit code based on whether outdated content was found
        return $this->exitCode($summary['total_outdated'] > 0);
    }

    /**
     * Display detailed information about outdated content.
     */
    protected function displayOutdatedDetails(array $outdated): void
    {
        // Agendas
        if ($outdated['agendas']->isNotEmpty()) {
            $this->newLine();
            $this->warn("=== OUTDATED AGENDAS ({$outdated['agendas']->count()}) ===");

            $agendaData = $outdated['agendas']->take(10)->map(function ($agenda) {
                $daysSince = $agenda->updated_at
                    ? now()->diffInDays($agenda->updated_at)
                    : now()->diffInDays($agenda->published_at);

                return [
                    $agenda->title,
                    $agenda->start_date?->format('Y-m-d') ?? 'N/A',
                    "{$daysSince} days ago",
                    $agenda->author?->name ?? 'Unknown',
                ];
            })->toArray();

            $this->table(
                ['Title', 'Start Date', 'Last Updated', 'Author'],
                $agendaData
            );

            if ($outdated['agendas']->count() > 10) {
                $this->info("... and " . ($outdated['agendas']->count() - 10) . " more");
            }
        }

        // Posts
        if ($outdated['posts']->isNotEmpty()) {
            $this->newLine();
            $this->warn("=== OUTDATED POSTS ({$outdated['posts']->count()}) ===");

            $postData = $outdated['posts']->take(10)->map(function ($post) {
                $daysSince = $post->updated_at
                    ? now()->diffInDays($post->updated_at)
                    : now()->diffInDays($post->published_at);

                return [
                    \Str::limit($post->title, 40),
                    $post->published_at?->format('Y-m-d') ?? 'N/A',
                    "{$daysSince} days ago",
                    $post->category?->name ?? 'Uncategorized',
                ];
            })->toArray();

            $this->table(
                ['Title', 'Published', 'Last Updated', 'Category'],
                $postData
            );

            if ($outdated['posts']->count() > 10) {
                $this->info("... and " . ($outdated['posts']->count() - 10) . " more");
            }
        }

        // Pages
        if ($outdated['pages']->isNotEmpty()) {
            $this->newLine();
            $this->warn("=== OUTDATED PAGES ({$outdated['pages']->count()}) ===");

            $pageData = $outdated['pages']->take(10)->map(function ($page) {
                $daysSince = $page->updated_at
                    ? now()->diffInDays($page->updated_at)
                    : now()->diffInDays($page->published_at);

                return [
                    $page->title,
                    $page->updated_at?->format('Y-m-d') ?? 'N/A',
                    "{$daysSince} days ago",
                ];
            })->toArray();

            $this->table(
                ['Title', 'Last Updated', 'Days Since Update'],
                $pageData
            );

            if ($outdated['pages']->count() > 10) {
                $this->info("... and " . ($outdated['pages']->count() - 10) . " more");
            }
        }

        // Downloads
        if ($outdated['downloads']->isNotEmpty()) {
            $this->newLine();
            $this->warn("=== OUTDATED DOWNLOADS ({$outdated['downloads']->count()}) ===");

            $downloadData = $outdated['downloads']->take(10)->map(function ($download) {
                $daysSince = $download->updated_at
                    ? now()->diffInDays($download->updated_at)
                    : now()->diffInDays($download->published_at);

                return [
                    $download->title,
                    $download->file_type ?? 'N/A',
                    "{$daysSince} days ago",
                ];
            })->toArray();

            $this->table(
                ['Title', 'File Type', 'Days Since Update'],
                $downloadData
            );

            if ($outdated['downloads']->count() > 10) {
                $this->info("... and " . ($outdated['downloads']->count() - 10) . " more");
            }
        }

        // No outdated content
        if (collect($outdated)->flatten()->isEmpty()) {
            $this->success('✓ No outdated content found! All content is up to date.');
        }
    }

    /**
     * Format outdated content for JSON output.
     */
    protected function formatOutdatedForJson(array $outdated): array
    {
        return [
            'agendas' => $outdated['agendas']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'start_date' => $item->start_date?->toIso8601String(),
                    'last_updated' => $item->updated_at?->toIso8601String(),
                ];
            })->toArray(),
            'posts' => $outdated['posts']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'published_at' => $item->published_at?->toIso8601String(),
                    'last_updated' => $item->updated_at?->toIso8601String(),
                ];
            })->toArray(),
            'pages' => $outdated['pages']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'last_updated' => $item->updated_at?->toIso8601String(),
                ];
            })->toArray(),
            'downloads' => $outdated['downloads']->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'last_updated' => $item->updated_at?->toIso8601String(),
                ];
            })->toArray(),
        ];
    }

    /**
     * Return appropriate exit code.
     */
    protected function exitCode(bool $hasOutdated): int
    {
        return $hasOutdated ? Command::FAILURE : Command::SUCCESS;
    }
}
