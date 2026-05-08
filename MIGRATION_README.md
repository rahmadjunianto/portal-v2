# Panduan Migrasi Database Portal Kemenag Nganjuk

## Overview

Dokumen ini menjelaskan cara menjalankan migrasi data dari database CMS lama ke database Laravel baru.

## Prerequisites

1. **Database Connection** - Pastikan koneksi `mysql_old` sudah dikonfigurasi di `.env`:

```env
DB_HOST_OLD=your_old_db_host
DB_PORT_OLD=3306
DB_DATABASE_OLD=your_old_database
DB_USERNAME_OLD=your_old_username
DB_PASSWORD_OLD=your_old_password

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portal_v2
DB_USERNAME=root
DB_PASSWORD=
```

2. **Konfigurasi `config/database.php`** - Tambahkan koneksi berikut:

```php
'mysql_old' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST_OLD', '127.0.0.1'),
    'port' => env('DB_PORT_OLD', '3306'),
    'database' => env('DB_DATABASE_OLD', 'forge'),
    'username' => env('DB_USERNAME_OLD', 'forge'),
    'password' => env('DB_PASSWORD_OLD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'options' => [
        \PDO::ATTR_EMULATE_PREPARES => true,
    ],
],
```

## Urutan Migrasi

Migrasi harus dijalankan dalam urutan berikut karena ada dependensi:

1. **Users** - Dasar untuk semua relasi author
2. **Settings** - Konfigurasi website
3. **Categories** - Kategori berita
4. **Pages** - Halaman statis
5. **Posts** - Berita (membutuhkan users & categories)
6. **Agendas** - Agenda
7. **Downloads** - File download
8. **External Links** - Link terkait
9. **Menus** - Struktur menu

## Cara Menjalankan

### A. Migrasi Lengkap (Recommended)

Jalankan semua migrasi dalam satu command:

```bash
php artisan migrate:legacy-portal
```

Dengan mode fresh (hapus semua data lama):
```bash
php artisan migrate:legacy-portal --fresh
```

### B. Migrasi Per-Modul

Jika ingin menjalankan migrasi per-modul:

```bash
# 1. Users
php artisan migrate:users
php artisan migrate:users --fresh    # Fresh migrate

# 2. Settings
php artisan migrate:settings

# 3. Categories
php artisan migrate:categories
php artisan migrate:categories --fresh

# 4. Pages
php artisan migrate:pages
php artisan migrate:pages --fresh

# 5. Posts
php artisan migrate:posts
php artisan migrate:posts --fresh

# 6. Agendas
php artisan migrate:agendas
php artisan migrate:agendas --fresh

# 7. Downloads
php artisan migrate:downloads
php artisan migrate:downloads --fresh

# 8. External Links
php artisan migrate:external-links
php artisan migrate:external-links --fresh

# 9. Menus
php artisan migrate:menus
php artisan migrate:menus --fresh
```

### C. Skip Modul Tertentu

```bash
php artisan migrate:legacy-portal --skip-users --skip-menus
```

### D. Custom Database Connection

```bash
php artisan migrate:legacy-portal --connection=mysql_old_custom
```

## Penjelasan Setiap Command

### migrate:users
- Memindahkan data dari tabel `users` lama
- Mapping role: admin, moderator, editor, user
- Password default: `changeme123` (harus diganti)

### migrate:settings
- Menggabungkan data dari tabel `identitas` dan `logo`
- Membuat singleton setting di database baru

### migrate:categories
- Memindahkan data dari tabel `kategori`
- Konversi enum `Y/N` ke boolean
- Generate slug otomatis jika kosong

### migrate:pages
- Memindahkan data dari tabel `halamanstatis`
- Relasi author berdasarkan username
- Gabung tanggal + jam jadi `published_at`

### migrate:posts
- Memindahkan data dari tabel `berita`
- **Parsing tags** dari string ke tabel pivot `post_tag`
- Mapping category & author dari cache
- Konversi headline/featured/aktif ke boolean

### migrate:agendas
- Memindahkan data dari tabel `agenda`
- Parse tanggal mulai & selesai
- Relasi author berdasarkan username

### migrate:downloads
- Memindahkan data dari tabel `download`
- Generate file path untuk storage
- Map extension ke file type category

### migrate:external-links
- Memindahkan data dari tabel `linkterkait`
- Extract category dari domain URL
- Normalisasi format URL

### migrate:menus
- Memindahkan data dari tabel `menu` & `menu_items`
- Buat header & footer menu
- Two-pass approach untuk parent relationships

## Hal yang Perlu Dicek Setelah Migrasi

### 1. Review Data
```bash
# Cek jumlah data
php artisan tinker --execute="echo App\Models\User::count();"
php artisan tinker --execute="echo App\Models\Post::count();"
php artisan tinker --execute="echo App\Models\Category::count();"
```

### 2. File Media
File media lama perlu disalin manual ke storage Laravel:

```bash
# Buat symbolic link
php artisan storage:link

# Salin file dari server lama
# Source: /path/to/old/uploads/
# Dest: storage/app/public/
```

Folder yang perlu disalin:
- `gambar/` - Gambar berita
- `logo/` - Logo website
- `download/` - File download
- `agenda/` - Gambar agenda

### 3. Password Users
Password semua user di-set ke `changeme123`. Ganti dengan:

```bash
# Via tinker
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->password = bcrypt('password_baru');
>>> $user->save();
```

### 4. Review Menu
Struktur menu lama mungkin perlu di-review manual karena:
- URL lama mungkin tidak valid di sistem baru
- Navigasi website redesign biasanya berubah

### 5. Generate Slugs
Slug yang duplikat sudah di-handle dengan suffix legacy_id. Review jika perlu.

## Troubleshooting

### Error: Koneksi database gagal
- Cek konfigurasi `.env` untuk `DB_HOST_OLD`, dll
- Pastikan database lama accessible
- Test koneksi: `mysql -h $DB_HOST_OLD -u $DB_USERNAME_OLD -p $DB_DATABASE_OLD`

### Error: Tabel tidak ditemukan
- Cek apakah nama tabel sesuai (case-sensitive)
- Cek prefix tabel di database lama

### Encoding Issue
- Semua command sudah handle konversi latin1 -> utf8mb4
- Jika masih ada karakter aneh, cek collation database lama

### Data Tidak Muncul
- Cek apakah `is_active` = true
- Cek apakah `published_at` sudah terisi
- Cek `status` = 'published'

## Struktur Database Baru

```
┌─────────────────┐     ┌──────────────────────┐
│     users       │────<│ posts (author_id)    │
└─────────────────┘     └──────────────────────┘
                               │
                               │ (pivot)
                               ▼
                         ┌──────────┐     ┌─────────┐
                         │  tags    │<────│post_tag │
                         └──────────┘     └─────────┘

┌─────────────────┐     ┌──────────────────────┐
│post_categories  │────<│   posts (category)   │
└─────────────────┘     └──────────────────────┘

┌─────────────────┐     ┌──────────────────────┐
│     users       │────<│     pages            │
└─────────────────┘     └──────────────────────┘

┌─────────────────┐     ┌──────────────────────┐
│     users       │────<│     agendas          │
└─────────────────┘     └──────────────────────┘

┌─────────────────┐
│    settings     │ (singleton)
└─────────────────┘

┌─────────────────┐
│ external_links  │
└─────────────────┘

┌─────────────────┐     ┌──────────────────────┐
│     menus       │────<│    menu_items         │
└─────────────────┘     └──────────────────────┘
                               │
                               │ (self-referencing)
                               ▼
                         ┌──────────────────────┐
                         │    menu_items         │ (parent_id)
                         └──────────────────────┘
```

## Catatan Penting

1. **Idempotent** - Semua command aman dijalankan ulang
2. **Uses updateOrCreate** - Tidak akan membuat duplikat
3. **Legacy ID Tracking** - Semua record baru menyimpan `legacy_id` untuk audit
4. **UTF-8 Compatible** - Handle encoding dari latin1
5. **Transaction** - Setiap batch adalah satu transaction (rollback on error)
