<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Download;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Cache duration for sitemap (1 hour).
     */
    protected int $cacheDuration = 3600;

    /**
     * Generate and return the sitemap XML.
     */
    public function index(): Response
    {
        $sitemap = Cache::remember('sitemap_xml', $this->cacheDuration, function () {
            return $this->generateSitemap();
        });

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600, must-revalidate',
        ]);
    }

    /**
     * Generate the complete sitemap XML content.
     */
    protected function generateSitemap(): string
    {
        $xml = new \XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('  ');
        $xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('urlset');
        $xml->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xml->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
        $xml->writeAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');

        // Static pages
        $this->addStaticPages($xml);

        // Posts/Berita
        $this->addPosts($xml);

        // Announcements/Pengumuman
        $this->addAnnouncements($xml);

        // Agendas
        $this->addAgendas($xml);

        // Pages
        $this->addPages($xml);

        // Downloads
        $this->addDownloads($xml);

        $xml->endElement(); // urlset
        $xml->endDocument();

        return $xml->outputMemory();
    }

    /**
     * Add static pages to sitemap.
     */
    protected function addStaticPages(\XMLWriter $xml): void
    {
        $staticPages = [
            [
                'loc' => config('app.url') . '/',
                'priority' => '1.0',
                'changefreq' => 'daily',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/berita',
                'priority' => '0.9',
                'changefreq' => 'hourly',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/pengumuman',
                'priority' => '0.8',
                'changefreq' => 'daily',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/agenda',
                'priority' => '0.8',
                'changefreq' => 'hourly',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/download',
                'priority' => '0.7',
                'changefreq' => 'daily',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/kontak',
                'priority' => '0.6',
                'changefreq' => 'monthly',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/profil',
                'priority' => '0.7',
                'changefreq' => 'monthly',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/aksesibilitas',
                'priority' => '0.5',
                'changefreq' => 'yearly',
                'lastmod' => now()->toIso8601String(),
            ],
            [
                'loc' => config('app.url') . '/kebijakan-privasi',
                'priority' => '0.5',
                'changefreq' => 'yearly',
                'lastmod' => now()->toIso8601String(),
            ],
        ];

        foreach ($staticPages as $page) {
            $this->addUrl($xml, $page['loc'], $page['lastmod'], $page['changefreq'], $page['priority']);
        }
    }

    /**
     * Add published posts to sitemap.
     */
    protected function addPosts(\XMLWriter $xml): void
    {
        $posts = Post::published()
            ->select(['slug', 'published_at', 'updated_at'])
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $lastmod = $post->updated_at?->toIso8601String() ?? $post->published_at?->toIso8601String() ?? now()->toIso8601String();

            $this->addUrl(
                $xml,
                config('app.url') . '/berita/' . $post->slug,
                $lastmod,
                'weekly',
                $post->is_headline ? '0.9' : '0.7'
            );
        }
    }

    /**
     * Add announcements to sitemap.
     */
    protected function addAnnouncements(\XMLWriter $xml): void
    {
        $announcements = Post::published()
            ->where('type', 'announcement')
            ->select(['slug', 'published_at', 'updated_at'])
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($announcements as $announcement) {
            $lastmod = $announcement->updated_at?->toIso8601String() ?? $announcement->published_at?->toIso8601String() ?? now()->toIso8601String();

            $this->addUrl(
                $xml,
                config('app.url') . '/berita/' . $announcement->slug,
                $lastmod,
                'weekly',
                '0.8'
            );
        }
    }

    /**
     * Add published agendas to sitemap.
     */
    protected function addAgendas(\XMLWriter $xml): void
    {
        $agendas = Agenda::published()
            ->select(['slug', 'published_at', 'updated_at'])
            ->orderBy('start_date', 'desc')
            ->get();

        foreach ($agendas as $agenda) {
            $lastmod = $agenda->updated_at?->toIso8601String() ?? $agenda->published_at?->toIso8601String() ?? now()->toIso8601String();

            $this->addUrl(
                $xml,
                config('app.url') . '/agenda/' . $agenda->slug,
                $lastmod,
                'weekly',
                '0.7'
            );
        }
    }

    /**
     * Add published pages to sitemap.
     */
    protected function addPages(\XMLWriter $xml): void
    {
        $pages = Page::whereNotNull('published_at')
            ->select(['slug', 'published_at', 'updated_at'])
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($pages as $page) {
            $lastmod = $page->updated_at?->toIso8601String() ?? $page->published_at?->toIso8601String() ?? now()->toIso8601String();

            $this->addUrl(
                $xml,
                config('app.url') . '/halaman/' . $page->slug,
                $lastmod,
                'monthly',
                '0.6'
            );
        }
    }

    /**
     * Add published downloads to sitemap.
     */
    protected function addDownloads(\XMLWriter $xml): void
    {
        $downloads = Download::where('is_published', true)
            ->whereNotNull('published_at')
            ->select(['slug', 'published_at', 'updated_at'])
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($downloads as $download) {
            $lastmod = $download->updated_at?->toIso8601String() ?? $download->published_at?->toIso8601String() ?? now()->toIso8601String();

            $this->addUrl(
                $xml,
                config('app.url') . '/download/' . $download->slug,
                $lastmod,
                'monthly',
                '0.5'
            );
        }
    }

    /**
     * Add a URL entry to the sitemap.
     */
    protected function addUrl(\XMLWriter $xml, string $loc, string $lastmod, string $changefreq, string $priority): void
    {
        $xml->startElement('url');
        $xml->writeElement('loc', $loc);
        $xml->writeElement('lastmod', $lastmod);
        $xml->writeElement('changefreq', $changefreq);
        $xml->writeElement('priority', $priority);
        $xml->endElement();
    }

    /**
     * Clear the sitemap cache.
     * Can be called via route or command after content update.
     */
    public function clearCache(): Response
    {
        Cache::forget('sitemap_xml');

        return response()->json([
            'success' => true,
            'message' => 'Sitemap cache cleared successfully'
        ]);
    }
}
