# Migrasi Database Portal Lama ke Laravel (Kemenag Nganjuk)

## Ringkasan

Dokumen ini menjelaskan proses migrasi dari database CMS lama (`portal`) ke database Laravel baru (`portal_v2`).

## Struktur Database Lama

Database: `portal`

| Tabel Lama | Deskripsi |
|------------|-----------|
| `users` | Data pengguna (username, password, level) |
| `kategori` | Kategori berita |
| `berita` | Berita/artikel (dengan tag string) |
| `tag` | Tabel tag (flat) |
| `halamanstatis` | Halaman statis |
| `agenda` | Agenda kegiatan |
| `download` | File download |
| `identitas` | Identitas website |
| `logo` | Logo website |
| `link_terkait` | Link terkait |
| `menu` | Menu items (flat, tanpa tabel menus) |

## Struktur Database Baru

Database: `portal_v2`

| Tabel Baru | Source | Legacy ID |
|------------|--------|-----------|
| `users` | users | legacy_username |
| `post_categories` | kategori | legacy_id |
| `posts` | berita | legacy_id |
| `tags` | berita.tag (parsed) | - |
| `post_tag` | berita.tag pivot | - |
| `pages` | halamanstatis | legacy_id |
| `agendas` | agenda | legacy_id |
| `downloads` | download | legacy_id |
| `settings` | identitas + logo | - |
| `external_links` | link_terkait | legacy_id |
| `menu_items` | menu | - |

> **Catatan:** Menu menggunakan `menu_items` langsung tanpa tabel `menus` untuk simplifikasi.

## Command Migrasi

### Cara Menjalankan

```bash
# Migrasi semua data sekaligus
php artisan migrate:legacy-portal

# Atau satu per satu
php artisan migrate:users
php artisan migrate:settings
php artisan migrate:categories
php artisan migrate:pages
php artisan migrate:posts
php artisan migrate:agendas
php artisan migrate:downloads
php artisan migrate:external-links
php artisan migrate:menus
```

### Mode Fresh (Hapus & Migrasi Ulang)

```bash
php artisan migrate:legacy-portal --fresh
```

## Urutan Migrasi

1. **Users** - Dasar untuk author_id
2. **Settings** - Konfigurasi website
3. **Categories** - Untuk post_categories
4. **Pages** - Tidak depend pada tabel lain
5. **Posts** - Butuh users dan categories
6. **Agendas** - Tidak depend pada tabel lain
7. **Downloads** - Tidak depend pada tabel lain
8. **External Links** - Tidak depend pada tabel lain
9. **Menus** - Menu items

## Transformasi Data

### Users
- `username` → `username` (unique)
- `nama_lengkap` → `name`
- `email` → `email`
- `no_telp` → `phone`
- `foto` → `photo`
- `level` → `role_name`
- `blokir` (Y/N) → `is_active`

### Posts (Berita)
- `id_berita` → `legacy_id`
- `id_kategori` → `category_id` (melalui mapping)
- `username` → `author_id` (melalui mapping)
- `judul` + `judul_seo` → `title` + `slug`
- `isi_berita` → `content`
- `gambar` → `thumbnail`
- `headline` (Y/N) → `is_headline`
- `tanggal` + `jam` → `published_at`
- `tag` (string) → `tags` + `post_tag` pivot

### Categories (Kategori)
- `id_kategori` → `legacy_id`
- `nama_kategori` → `name`
- `kategori_seo` → `slug`

### Menu Items
- `id_menu` → `legacy_id`
- `id_parent` → `parent_id`
- `nama_menu` → `title`
- `link` → `url`
- `aktif` (Ya/Tidak) → `is_active`
- `urutan` → `sort_order`
- `position` → `location` (Top/Footer/Bottom)

## Status Migrasi (Per 10 Mei 2026)

| Tabel | Status | Records |
|-------|--------|---------|
| users | ✅ Selesai | 57 |
| post_categories | ✅ Selesai | 7 |
| posts | ✅ Selesai | 1169 |
| tags | ✅ Selesai | - |
| pages | ✅ Selesai | - |
| agendas | ✅ Selesai | - |
| downloads | ✅ Selesai | - |
| settings | ✅ Selesai | 1 |
| external_links | ✅ Selesai | - |
| menu_items | ✅ Selesai | - |

## Langkah Setelah Migrasi

1. **Salin file media:**
   ```bash
   cp -r /path/to/old/portal/images/* storage/app/public/images/
   php artisan storage:link
   ```

2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Test frontend**

## Catatan Penting

- Encoding lama (latin1) dikonversi otomatis ke UTF-8
- Field kosong → null
- Slug duplikat → suffix `-legacy_id`
- Semua command idempotent (aman dijalankan ulang)
- Menu tidak menggunakan tabel `menus` (flat structure)
