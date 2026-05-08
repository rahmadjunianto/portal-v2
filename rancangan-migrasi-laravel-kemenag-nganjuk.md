# Rancangan Migrasi Laravel dan Transformasi Database

Dokumen ini disusun dari struktur database existing `u332226767_portal-1.sql` yang memuat tabel seperti `users`, `kategori`, `berita`, `halamanstatis`, `agenda`, `download`, `identitas`, `linkterkait`, `menu`, `album`, `gallery`, `playlist`, `video`, `tag`, dan `statistik`. [file:5]

## 1. Struktur tabel baru

### users
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('username', 50)->unique();
    $table->string('name', 100);
    $table->string('email', 100)->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('photo')->nullable();
    $table->string('password');
    $table->string('role_name', 20)->default('user');
    $table->boolean('is_active')->default(true);
    $table->string('legacy_username', 50)->nullable();
    $table->rememberToken();
    $table->timestamps();
});
```

### post_categories
```php
Schema::create('post_categories', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->string('name', 100);
    $table->string('slug', 120)->unique();
    $table->boolean('is_active')->default(true);
    $table->boolean('show_in_sidebar')->default(false);
    $table->timestamps();
});
```

### posts
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->foreignId('category_id')->nullable()->constrained('post_categories')->nullOnDelete();
    $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('title', 255);
    $table->string('subtitle', 255)->nullable();
    $table->string('slug', 255)->unique();
    $table->longText('content');
    $table->string('thumbnail')->nullable();
    $table->text('image_caption')->nullable();
    $table->string('youtube_url')->nullable();
    $table->enum('type', ['berita', 'pengumuman', 'lowongan'])->default('berita');
    $table->boolean('is_headline')->default(false);
    $table->boolean('is_featured')->default(false);
    $table->boolean('is_active')->default(true);
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->unsignedBigInteger('views')->default(0);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### tags dan post_tag
```php
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('slug', 120)->unique();
    $table->timestamps();
});

Schema::create('post_tag', function (Blueprint $table) {
    $table->id();
    $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
    $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
    $table->unique(['post_id', 'tag_id']);
});
```

### pages
```php
Schema::create('pages', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('title', 255);
    $table->string('slug', 255)->unique();
    $table->longText('content');
    $table->string('cover_image')->nullable();
    $table->enum('page_type', ['profil', 'unit', 'lembaga', 'regulasi', 'statis'])->default('statis');
    $table->unsignedBigInteger('views')->default(0);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### agendas
```php
Schema::create('agendas', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
    $table->string('title', 255);
    $table->string('slug', 255)->unique();
    $table->longText('description');
    $table->string('location', 255)->nullable();
    $table->string('sender_name', 100)->nullable();
    $table->string('image')->nullable();
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->unsignedBigInteger('views')->default(0);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### downloads
```php
Schema::create('downloads', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->string('title', 255);
    $table->string('slug', 255)->unique();
    $table->string('file_name', 255);
    $table->string('file_path', 255)->nullable();
    $table->string('file_type', 50)->nullable();
    $table->unsignedBigInteger('file_size')->nullable();
    $table->unsignedBigInteger('downloads_count')->default(0);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

### settings
```php
Schema::create('settings', function (Blueprint $table) {
    $table->id();
    $table->string('site_name', 150);
    $table->string('site_url', 255)->nullable();
    $table->string('email', 100)->nullable();
    $table->string('phone', 20)->nullable();
    $table->string('logo')->nullable();
    $table->string('favicon')->nullable();
    $table->text('meta_description')->nullable();
    $table->string('meta_keywords', 255)->nullable();
    $table->text('maps_embed')->nullable();
    $table->text('footer_description')->nullable();
    $table->text('facebook_url')->nullable();
    $table->timestamps();
});
```

### external_links
```php
Schema::create('external_links', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->string('title', 255);
    $table->text('description')->nullable();
    $table->text('url');
    $table->string('category', 100)->nullable();
    $table->boolean('is_active')->default(true);
    $table->unsignedInteger('sort_order')->default(0);
    $table->timestamps();
});
```

### menus dan menu_items
```php
Schema::create('menus', function (Blueprint $table) {
    $table->id();
    $table->string('name', 100);
    $table->string('location', 50);
    $table->timestamps();
});

Schema::create('menu_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('legacy_id')->nullable()->unique();
    $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
    $table->foreignId('parent_id')->nullable()->constrained('menu_items')->nullOnDelete();
    $table->string('title', 150);
    $table->string('url', 255)->nullable();
    $table->boolean('is_active')->default(true);
    $table->boolean('target_blank')->default(false);
    $table->unsignedInteger('sort_order')->default(0);
    $table->timestamps();
});
```

## 2. Mapping transformasi data

### users -> users
- `username` -> `username`
- `namalengkap` -> `name`
- `email` -> `email`
- `notelp` -> `phone`
- `foto` -> `photo`
- `password` -> `password`
- `level` -> `role_name`
- `blokir='N'` -> `is_active=1`, `blokir='Y'` -> `is_active=0` [file:5]

### kategori -> post_categories
- `idkategori` -> `legacy_id`
- `namakategori` -> `name`
- `kategoriseo` -> `slug`
- `aktif` -> `is_active`
- `sidebar` -> `show_in_sidebar` [file:5]

### berita -> posts
- `idberita` -> `legacy_id`
- `idkategori` -> `category_id`
- `username` -> `author_id`
- `judul` -> `title`
- `subjudul` -> `subtitle`
- `judulseo` -> `slug`
- `isiberita` -> `content`
- `gambar` -> `thumbnail`
- `keterangangambar` -> `image_caption`
- `youtube` -> `youtube_url`
- `dibaca` -> `views`
- `headline` -> `is_headline`
- `utama` -> `is_featured`
- `aktif` -> `is_active`
- `tanggal + jam` -> `published_at`
- `status` + `aktif` -> `status` published/draft [file:5]

### halamanstatis -> pages
- `idhalaman` -> `legacy_id`
- `judul` -> `title`
- `judulseo` -> `slug`
- `isihalaman` -> `content`
- `gambar` -> `cover_image`
- `username` -> `author_id`
- `dibaca` -> `views`
- `tglposting + jam` -> `published_at` [file:5]

### agenda -> agendas
- `idagenda` -> `legacy_id`
- `tema` -> `title`
- `temaseo` -> `slug`
- `isiagenda` -> `description`
- `tempat` -> `location`
- `pengirim` -> `sender_name`
- `gambar` -> `image`
- `tglmulai` -> `start_date`
- `tglselesai` -> `end_date`
- `dibaca` -> `views`
- `username` -> `author_id`
- `tglposting + jam` -> `published_at` [file:5]

### download -> downloads
- `iddownload` -> `legacy_id`
- `judul` -> `title`
- `namafile` -> `file_name`
- `tglposting` -> `published_at`
- `hits` -> `downloads_count` [file:5]

### identitas + logo -> settings
- `namawebsite` -> `site_name`
- `url` -> `site_url`
- `email` -> `email`
- `notelp` -> `phone`
- `metadeskripsi` -> `meta_description`
- `metakeyword` -> `meta_keywords`
- `favicon` -> `favicon`
- `maps` -> `maps_embed`
- `keterangan` -> `footer_description`
- `facebook` -> `facebook_url`
- `logo.gambar` -> `logo` [file:5]

### linkterkait -> external_links
- `idlinkterkait` -> `legacy_id`
- `judulmenu` -> `title`
- `detailmenu` -> `description`
- `link` -> `url` [file:5]

### menu -> menus/menu_items
- `position='Top'` -> menu `header`
- `position='Bottom'` -> menu `footer`
- `idmenu` -> `legacy_id`
- `idparent` -> `parent_id`
- `namamenu` -> `title`
- `link` -> `url`
- `aktif` -> `is_active`
- `target` -> `target_blank`
- `urutan` -> `sort_order` [file:5]

## 3. Contoh command migrasi data

### Migrasi user
```php
$oldUsers = DB::connection('mysql_old')->table('users')->get();

foreach ($oldUsers as $row) {
    User::updateOrCreate(
        ['username' => $row->username],
        [
            'name' => $row->namalengkap,
            'email' => $row->email ?: null,
            'phone' => $row->notelp ?: null,
            'photo' => $row->foto ?: null,
            'password' => $row->password,
            'role_name' => $row->level ?: 'user',
            'is_active' => $row->blokir === 'N',
            'legacy_username' => $row->username,
        ]
    );
}
```

### Migrasi kategori
```php
$rows = DB::connection('mysql_old')->table('kategori')->get();

foreach ($rows as $row) {
    PostCategory::updateOrCreate(
        ['legacy_id' => $row->idkategori],
        [
            'name' => $row->namakategori,
            'slug' => $row->kategoriseo ?: Str::slug($row->namakategori),
            'is_active' => $row->aktif === 'Y',
            'show_in_sidebar' => (int) $row->sidebar > 0,
        ]
    );
}
```

### Migrasi berita
```php
$rows = DB::connection('mysql_old')->table('berita')->get();

foreach ($rows as $row) {
    $author = User::where('username', $row->username)->first();
    $category = PostCategory::where('legacy_id', $row->idkategori)->first();

    $post = Post::updateOrCreate(
        ['legacy_id' => $row->idberita],
        [
            'category_id' => $category?->id,
            'author_id' => $author?->id,
            'title' => $row->judul,
            'subtitle' => $row->subjudul ?: null,
            'slug' => $row->judulseo ?: Str::slug($row->judul . '-' . $row->idberita),
            'content' => $row->isiberita,
            'thumbnail' => $row->gambar ?: null,
            'image_caption' => $row->keterangangambar ?: null,
            'youtube_url' => $row->youtube ?: null,
            'type' => 'berita',
            'is_headline' => $row->headline === 'Y',
            'is_featured' => $row->utama === 'Y',
            'is_active' => $row->aktif === 'Y',
            'status' => ($row->aktif === 'Y' && $row->status === 'Y') ? 'published' : 'draft',
            'views' => $row->dibaca ?? 0,
            'published_at' => trim($row->tanggal . ' ' . $row->jam),
        ]
    );

    if (!empty($row->tag)) {
        $tags = array_filter(array_map('trim', preg_split('/[,;]+/', $row->tag)));
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
            $post->tags()->syncWithoutDetaching([$tag->id]);
        }
    }
}
```

## 4. Checklist teknis

- Pastikan koneksi `mysql_old` tersedia untuk database lama. [file:5]
- Karena database lama banyak memakai `latin1`, uji encoding sebelum migrasi penuh. [file:5]
- Salin file fisik gambar berita, gambar halaman, file download, logo, favicon, dan media lain ke `storage/app/public`. [file:5]
- Tambahkan field `legacy_id` untuk audit hasil migrasi. [file:5]
- Validasi jumlah data setelah import: kategori, berita, halaman, agenda, download, dan link terkait. [file:5]
- Menu lama sebaiknya direview manual walaupun bisa dimigrasikan otomatis, karena struktur navigasi redesign biasanya berubah. [file:5]

## 5. Urutan eksekusi

1. Jalankan migration tabel baru.  
2. Migrasi `users`.  
3. Migrasi `post_categories`.  
4. Migrasi `settings`.  
5. Migrasi `pages`.  
6. Migrasi `posts` dan `tags`.  
7. Migrasi `agendas`.  
8. Migrasi `downloads`.  
9. Migrasi `external_links`.  
10. Migrasi `menus` dan `menu_items`. [file:5]

Dokumen ini bisa langsung dijadikan dasar implementasi Laravel dan penyusunan command migrasi data. [file:5]
