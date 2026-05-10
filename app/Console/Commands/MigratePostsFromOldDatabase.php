<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MigratePostsFromOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:posts
                            {--fresh : Drop all existing posts before migrating}
                            {--connection=mysql_old : Database connection name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate posts (berita) from old database to new portal database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = $this->option('connection');
        $isFresh = $this->option('fresh');

        $this->info('===========================================');
        $this->info('  Migrasi Berita dari Database Lama');
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
        $oldTableExists = DB::connection($connection)->getSchemaBuilder()->hasTable('berita');
        if (!$oldTableExists) {
            $this->warn('⚠ Tabel "berita" tidak ditemukan di database lama. Skip migrasi.');
            return self::SUCCESS;
        }

        // Fresh option
        if ($isFresh) {
            $this->warn('Mode fresh aktif - menghapus semua berita yang sudah dimigrasikan...');
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0');
            Post::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->info('✓ Semua berita berhasil dihapus');
        }

        $this->newLine();
        $this->info('Memulai migrasi berita...');
        $this->newLine();

        // Cache user mapping for performance
        $this->info('  • Membuat cache mapping users...');
        $userMapping = $this->buildUserMapping($connection);
        $this->info('    ✓ ' . count($userMapping) . ' user di-cache');

        // Cache category mapping
        $this->info('  • Membuat cache mapping kategori...');
        $categoryMapping = $this->buildCategoryMapping($connection);
        $this->info('    ✓ ' . count($categoryMapping) . ' kategori di-cache');

        $this->newLine();

        $oldPosts = DB::connection($connection)->table('berita')->get();
        $total = $oldPosts->count();
        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $created = 0;
        $updated = 0;
        $tagsCreated = 0;
        $errors = [];

        DB::beginTransaction();

        try {
            foreach ($oldPosts as $row) {
                // Nama kolom dengan underscore: id_berita, judul_seo, dll
                $legacyId = $row->id_berita ?? null;
                $title = $this->sanitizeString($row->judul ?? '');
                $slug = $this->sanitizeString($row->judul_seo ?? '');
                $content = $this->sanitizeContent($row->isi_berita ?? '');
                $thumbnail = $this->sanitizeString($row->gambar ?? '');
                $imageCaption = $this->sanitizeString($row->keterangan_gambar ?? '');
                $youtubeUrl = $this->sanitizeString($row->youtube ?? '');
                $username = $this->sanitizeString($row->username ?? '');
                $idkategori = $row->id_kategori ?? null;
                $tagString = $this->sanitizeString($row->tag ?? '');
                $headline = $row->headline ?? 'N';
                $utama = $row->utama ?? 'N';
                $aktif = $row->aktif ?? 'Y';
                $status = $row->status ?? 'N';
                $dibaca = $row->dibaca ?? 0;
                $tanggal = $row->tanggal ?? null;
                $jam = $row->jam ?? '00:00:00';
                $tagString = $this->sanitizeString($row->tag ?? '');

                // Validasi
                if (empty($legacyId)) {
                    $errors[] = "Berita tanpa ID, dilewati";
                    $progressBar->advance();
                    continue;
                }

                if (empty($title)) {
                    $errors[] = "Berita ID {$legacyId} tidak memiliki judul, dilewati";
                    $progressBar->advance();
                    continue;
                }

                // Get author ID from cache
                $authorId = $userMapping[$username] ?? null;

                // Get category ID from cache
                $categoryId = $categoryMapping[$idkategori] ?? null;

                // Generate slug jika kosong
                if (empty($slug)) {
                    $slug = Str::slug($title . '-' . $legacyId);
                }

                // Handle duplicate slug
                $originalSlug = $slug;
                $counter = 1;
                while (Post::where('slug', $slug)->where('legacy_id', '!=', $legacyId)->exists()) {
                    $slug = $originalSlug . '-' . $legacyId;
                }

                // Combine date and time for published_at
                $publishedAt = null;
                if ($tanggal) {
                    $publishedAt = $this->combineDateTime($tanggal, $jam);
                }

                // Determine status
                $postStatus = ($aktif === 'Y' && $status === 'Y') ? 'published' : 'draft';

                // Determine type (default berita)
                $type = 'berita';
                if (str_contains(strtolower($title), 'pengumuman')) {
                    $type = 'pengumuman';
                } elseif (str_contains(strtolower($title), 'lowongan')) {
                    $type = 'lowongan';
                }

                $data = [
                    'category_id' => $categoryId,
                    'author_id' => $authorId,
                    'title' => $title,
                    'subtitle' => $this->sanitizeString($row->subjudul ?? '') ?: null,
                    'slug' => $slug,
                    'content' => $content,
                    'thumbnail' => $thumbnail ? 'posts/' . $thumbnail : null,
                    'image_caption' => $imageCaption ?: null,
                    'youtube_url' => $youtubeUrl ?: null,
                    'type' => $type,
                    'is_headline' => $headline === 'Y',
                    'is_featured' => $utama === 'Y',
                    'is_active' => $aktif === 'Y',
                    'status' => $postStatus,
                    'views' => (int) $dibaca,
                    'published_at' => $publishedAt,
                ];

                $post = Post::updateOrCreate(
                    ['legacy_id' => $legacyId],
                    $data
                );

                if ($post->wasRecentlyCreated) {
                    $created++;
                    Log::info("Created post: {$title}");
                } else {
                    $updated++;
                    Log::info("Updated post: {$title}");
                }

                // Parse and attach tags
                if (!empty($tagString)) {
                    $tagsCreated += $this->parseAndAttachTags($post, $tagString);
                }

                $progressBar->advance();
            }

            DB::commit();
            $progressBar->finish();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->newLine(2);
            $this->error("✗ Error migrasi: " . $e->getMessage());
            Log::error('Migrasi Posts Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return self::FAILURE;
        }

        $this->newLine(2);
        $this->info('===========================================');
        $this->info('  Hasil Migrasi Berita');
        $this->info('===========================================');
        $this->line("  Total data lama: {$total}");
        $this->line("  Berhasil dibuat: {$created}");
        $this->line("  Berhasil diupdate: {$updated}");
        $this->line("  Tags dibuat: {$tagsCreated}");
        $this->line("  Total berita sekarang: " . Post::count());
        $this->line("  Total tags sekarang: " . Tag::count());

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
        $this->info('✓ Migrasi berita selesai!');
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
     * Build category mapping from old database.
     */
    private function buildCategoryMapping(string $connection): array
    {
        $mapping = [];
        $categories = DB::connection($connection)->table('kategori')->get();

        foreach ($categories as $category) {
            $legacyId = $category->id_kategori ?? null;
            if ($legacyId) {
                $newCategory = PostCategory::where('legacy_id', $legacyId)->first();
                if ($newCategory) {
                    $mapping[$legacyId] = $newCategory->id;
                }
            }
        }

        return $mapping;
    }

    /**
     * Parse tags string and attach to post.
     */
    private function parseAndAttachTags(Post $post, string $tagString): int
    {
        // Split by comma or semicolon
        $tagNames = array_filter(array_map('trim', preg_split('/[,;]+/', $tagString)));
        $count = 0;

        foreach ($tagNames as $tagName) {
            if (empty($tagName)) {
                continue;
            }

            $tagSlug = Str::slug($tagName);

            // Create tag if not exists
            $tag = Tag::firstOrCreate(
                ['slug' => $tagSlug],
                ['name' => $tagName]
            );

            // Attach to post (syncWithoutDetaching avoids duplicates)
            if (!$post->tags()->where('tag_id', $tag->id)->exists()) {
                $post->tags()->attach($tag->id);
                $count++;
            }
        }

        return $count;
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

            if (empty($time) || $time === '00:00:00') {
                $time = '00:00:00';
            }

            $datetime = $date . ' ' . $time;

            if (preg_match('/^\d{4}-\d{2}-\d{2}/', $datetime)) {
                return $datetime;
            }

            $timestamp = strtotime($datetime);
            if ($timestamp) {
                return date('Y-m-d H:i:s', $timestamp);
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
