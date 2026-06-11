<?php

namespace App\Services;

use App\Models\Agenda;
use App\Models\Download;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ContentMonitorService
{
    /**
     * Days threshold for outdated content.
     */
    protected int $thresholdDays;

    /**
     * Create a new service instance.
     */
    public function __construct(int $thresholdDays = 180)
    {
        $this->thresholdDays = $thresholdDays;
    }

    /**
     * Get all outdated content across all models.
     */
    public function getOutdatedContent(): array
    {
        return [
            'agendas' => $this->getOutdatedAgendas(),
            'posts' => $this->getOutdatedPosts(),
            'pages' => $this->getOutdatedPages(),
            'downloads' => $this->getOutdatedDownloads(),
        ];
    }

    /**
     * Get all outdated agendas.
     */
    public function getOutdatedAgendas(): Collection
    {
        $threshold = now()->subDays($this->thresholdDays);

        return Agenda::published()
            ->where('updated_at', '<', $threshold)
            ->orWhere(function ($query) use ($threshold) {
                $query->whereNull('updated_at')
                      ->where('published_at', '<', $threshold);
            })
            ->with('author')
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    /**
     * Get all outdated posts.
     */
    public function getOutdatedPosts(): Collection
    {
        $threshold = now()->subDays($this->thresholdDays);

        return Post::published()
            ->where('updated_at', '<', $threshold)
            ->orWhere(function ($query) use ($threshold) {
                $query->whereNull('updated_at')
                      ->where('published_at', '<', $threshold);
            })
            ->with(['author', 'category'])
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    /**
     * Get all outdated pages.
     */
    public function getOutdatedPages(): Collection
    {
        $threshold = now()->subDays($this->thresholdDays);

        return Page::whereNotNull('published_at')
            ->where(function ($query) use ($threshold) {
                $query->where('updated_at', '<', $threshold)
                      ->orWhere(function ($q) use ($threshold) {
                          $q->whereNull('updated_at')
                            ->where('published_at', '<', $threshold);
                      });
            })
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    /**
     * Get all outdated downloads.
     */
    public function getOutdatedDownloads(): Collection
    {
        $threshold = now()->subDays($this->thresholdDays);

        return Download::where('is_published', true)
            ->where(function ($query) use ($threshold) {
                $query->where('updated_at', '<', $threshold)
                      ->orWhere(function ($q) use ($threshold) {
                          $q->whereNull('updated_at')
                            ->where('published_at', '<', $threshold);
                      });
            })
            ->orderBy('updated_at', 'asc')
            ->get();
    }

    /**
     * Get summary statistics.
     */
    public function getSummary(): array
    {
        $outdated = $this->getOutdatedContent();

        return [
            'total_outdated' => collect($outdated)->flatten()->count(),
            'outdated_agendas' => $outdated['agendas']->count(),
            'outdated_posts' => $outdated['posts']->count(),
            'outdated_pages' => $outdated['pages']->count(),
            'outdated_downloads' => $outdated['downloads']->count(),
            'threshold_days' => $this->thresholdDays,
            'checked_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Get content that needs urgent attention (> 365 days).
     */
    public function getUrgentContent(): array
    {
        $threshold = now()->subDays(365);

        return [
            'agendas' => Agenda::published()
                ->where('updated_at', '<', $threshold)
                ->count(),
            'posts' => Post::published()
                ->where('updated_at', '<', $threshold)
                ->count(),
            'pages' => Page::whereNotNull('published_at')
                ->where('updated_at', '<', $threshold)
                ->count(),
            'downloads' => Download::where('is_published', true)
                ->where('updated_at', '<', $threshold)
                ->count(),
        ];
    }

    /**
     * Mark content as reviewed (update timestamp).
     */
    public function markAsReviewed(string $type, int $id): bool
    {
        $model = $this->getModel($type);

        if (!$model) {
            return false;
        }

        return $model::where('id', $id)->update(['updated_at' => now()]) > 0;
    }

    /**
     * Archive old agendas (end_date passed).
     */
    public function archiveOldAgendas(): int
    {
        return Agenda::where('end_date', '<', now()->subDays(30))
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    /**
     * Get model class by type.
     */
    protected function getModel(string $type): ?string
    {
        $models = [
            'agenda' => Agenda::class,
            'agendas' => Agenda::class,
            'post' => Post::class,
            'posts' => Post::class,
            'page' => Page::class,
            'pages' => Page::class,
            'download' => Download::class,
            'downloads' => Download::class,
        ];

        return $models[$type] ?? null;
    }

    /**
     * Set threshold days.
     */
    public function setThreshold(int $days): self
    {
        $this->thresholdDays = $days;
        return $this;
    }
}
