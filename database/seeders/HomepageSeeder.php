<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Download;
use App\Models\ExternalLink;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomepageSeeder extends Seeder
{
    /**
     * Seed data for homepage testing.
     */
    public function run(): void
    {
        // 1. Create Settings (singleton)
        $setting = Setting::firstOrCreate(
            ['id' => 1],
            [
                'site_name' => 'Portal Kemenag Nganjuk',
                'site_url' => 'https://kemenag-nganjuk.go.id',
                'email' => 'info@kemenag-nganjuk.go.id',
                'phone' => '(0358) 321056',
                'logo' => null,
                'favicon' => null,
                'meta_description' => 'Portal Resmi Kantor Kementerian Agama Kabupaten Nganjuk - Menyajikan informasi keagamaan dan layanan publik',
                'meta_keywords' => 'kemenag, nganjuk, agama, islam, pelayanan publik',
                'footer_description' => 'Portal resmi Kantor Kementerian Agama Kabupaten Nganjuk. Menyajikan informasi seputar kegiatan, layanan, dan berita keagamaan di Kabupaten Nganjuk.',
                'facebook_url' => 'https://facebook.com/kemenagnganjuk',
            ]
        );

        // 2. Create Users (without role field if not exists)
        $adminData = [
            'name' => 'Administrator',
            'password' => bcrypt('password'),
        ];
        if (Schema::hasColumn('users', 'role')) {
            $adminData['role'] = 'admin';
        }
        $admin = User::firstOrCreate(
            ['email' => 'admin@kemenag.go.id'],
            $adminData
        );

        // 3. Create Post Categories
        $categories = [
            ['name' => 'Berita', 'slug' => 'berita'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman'],
            ['name' => 'Artikel', 'slug' => 'artikel'],
            ['name' => 'Prestasi', 'slug' => 'prestrasi'],
        ];

        foreach ($categories as $category) {
            PostCategory::firstOrCreate(['slug' => $category['slug']], $category);
        }

        // 4. Create Posts
        $postsData = [
            [
                'title' => 'KakanKemenag Nganjuk Buka Seleksi Calon Kepala Madrasah',
                'subtitle' => 'Pendaftaran dibuka untuk putra-putri terbaik bangsa yang memiliki kompetensi di bidang pendidikan',
                'category' => 'Berita',
                'is_headline' => true,
                'is_featured' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => 'Pelantikan Pejabat Struktural di Lingkungan Kanwil Kemenag Jawa Timur',
                'subtitle' => 'Serangkaian pelantikan dilakukan untuk meningkatkan pelayanan kepada masyarakat',
                'category' => 'Berita',
                'is_headline' => false,
                'is_featured' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Workshop Peningkatan Kapasitas Guru RA di Kabupaten Nganjuk',
                'subtitle' => 'Guru-guru RA mendapat pelatihan inovatif untuk pembelajaran abad 21',
                'category' => 'Artikel',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Monitoring dan Evaluasi Blockgrant Madrasah Tahun 2024',
                'subtitle' => 'Tim Kanwil Kemenag melakukan monev ke beberapa madrasah di wilayah Nganjuk',
                'category' => 'Berita',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Pengumuman Libur Nasional dan Cuti Bersama 2024',
                'subtitle' => 'Berikut jadwal libur nasional dan cuti bersama tahun 2024',
                'category' => 'Pengumuman',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Sertifikasi Halal UMKM Masjid se-Kabupaten Nganjuk',
                'subtitle' => 'Program sertifikasi halal untuk meningkatkan kualitas produk UMKM masjid',
                'category' => 'Artikel',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Pelaksanaan Ujian Madrasah Tsanawiyah 2024',
                'subtitle' => 'Ujian Madrasah Tsanawiyah akan dilaksanakan secara daring',
                'category' => 'Pengumuman',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Santunan Anak Yatim基金会 Pentecost di Nganjuk',
                'subtitle' => 'Bantuan untuk anak yatim dari berbagai фонд yang ada di Nganjuk',
                'category' => 'Berita',
                'is_headline' => false,
                'is_featured' => false,
                'published_at' => now()->subDays(8),
            ],
        ];

        foreach ($postsData as $postData) {
            $category = PostCategory::where('name', $postData['category'])->first();
            Post::firstOrCreate(
                ['slug' => Str::slug($postData['title'])],
                [
                    'title' => $postData['title'],
                    'subtitle' => $postData['subtitle'],
                    'slug' => Str::slug($postData['title']),
                    'content' => '<p>' . $postData['subtitle'] . '</p><p>Untuk informasi lebih lengkap, silakan hubungi kantor Kementerian Agama Kabupaten Nganjuk.</p>',
                    'category_id' => $category->id ?? null,
                    'author_id' => $admin->id,
                    'status' => 'published',
                    'is_active' => true,
                    'is_headline' => $postData['is_headline'],
                    'is_featured' => $postData['is_featured'],
                    'published_at' => $postData['published_at'],
                ]
            );
        }

        // 5. Create Agendas
        $agendasData = [
            [
                'title' => 'Rapat Koordinasi TPQ se-Kabupaten Nganjuk',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7),
                'location' => 'Gedung Serbaguna Kemenag Nganjuk',
                'event_time_text' => '08.00 - 12.00 WIB',
            ],
            [
                'title' => 'Pelaksanaan Ujian Madrasah Aliyah',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(16),
                'location' => 'Berbagai Madrasah Aliyah di Nganjuk',
                'event_time_text' => '07.00 - selesai',
            ],
            [
                'title' => 'Sosialisasi Pendaftaran Haji 2025',
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(21),
                'location' => 'Aula Kanwil Kemenag Jawa Timur',
                'event_time_text' => '09.00 - 11.00 WIB',
            ],
            [
                'title' => 'Workshop Media Center Kemenag',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(30),
                'location' => 'Kantor Kemenag Kabupaten Nganjuk',
                'event_time_text' => '13.00 - 16.00 WIB',
            ],
        ];

        foreach ($agendasData as $agendaData) {
            Agenda::firstOrCreate(
                ['slug' => Str::slug($agendaData['title'])],
                [
                    'title' => $agendaData['title'],
                    'slug' => Str::slug($agendaData['title']),
                    'description' => $agendaData['title'],
                    'start_date' => $agendaData['start_date'],
                    'end_date' => $agendaData['end_date'],
                    'location' => $agendaData['location'],
                    'event_time_text' => $agendaData['event_time_text'],
                    'author_id' => $admin->id,
                    'published_at' => now(),
                ]
            );
        }

        // 6. Create Downloads
        $downloadsData = [
            ['title' => 'Permohonan Rekomendasi Penelitian', 'file_type' => 'PDF'],
            ['title' => 'Formulir Pendaftaran Haji', 'file_type' => 'PDF'],
            ['title' => 'Pedoman Gestão Zakat', 'file_type' => 'PDF'],
            ['title' => 'Juknis Blockgrant Madrasah 2024', 'file_type' => 'PDF'],
            ['title' => 'Template Laporan Pertanggungjawaban', 'file_type' => 'DOCX'],
            ['title' => 'Panduan Pendaftaran Nikah', 'file_type' => 'PDF'],
        ];

        foreach ($downloadsData as $downloadData) {
            Download::firstOrCreate(
                ['slug' => Str::slug($downloadData['title'])],
                [
                    'title' => $downloadData['title'],
                    'slug' => Str::slug($downloadData['title']),
                    'file_name' => Str::slug($downloadData['title']) . '.' . strtolower($downloadData['file_type']),
                    'file_path' => 'documents/' . Str::slug($downloadData['title']) . '.' . strtolower($downloadData['file_type']),
                    'file_type' => $downloadData['file_type'],
                    'file_size' => rand(100000, 5000000), // Random file size
                    'published_at' => now()->subDays(rand(1, 30)),
                ]
            );
        }

        // 7. Create External Links
        $externalLinksData = [
            ['title' => 'Sistem Informasi Haji', 'url' => 'https://haji.kemenag.go.id', 'category' => 'Layanan'],
            ['title' => 'SIHON', 'url' => 'https://sihon.kemenag.go.id', 'category' => 'Layanan'],
            ['title' => 'SIAP SRW', 'url' => 'https://siapsrw.kemenag.go.id', 'category' => 'Pendidikan'],
            ['title' => 'EMIS', 'url' => 'https://emis.kemenag.go.id', 'category' => 'Pendidikan'],
            ['title' => 'Bimaskhas', 'url' => 'https://bimaskhas.kemenag.go.id', 'category' => 'Pendidikan'],
            ['title' => 'Zakat Digital', 'url' => 'https://zakat.kemenag.go.id', 'category' => 'Layanan'],
            ['title' => 'Siaran Pers', 'url' => 'https://kemenag.go.id/berita', 'category' => 'Informasi'],
            ['title' => 'JDIH', 'url' => 'https://jdih.kemenag.go.id', 'category' => 'Regulasi'],
            ['title' => 'PPID', 'url' => 'https://ppid.kemenag.go.id', 'category' => 'Layanan'],
            ['title' => 'LAPOR!', 'url' => 'https://lapor.go.id', 'category' => 'Layanan'],
            ['title' => 'SIMPEG', 'url' => 'https://simpeg.kemenag.go.id', 'category' => 'Internal'],
            ['title' => 'e-Office', 'url' => 'https://eoffice.kemenag.go.id', 'category' => 'Internal'],
        ];

        foreach ($externalLinksData as $index => $linkData) {
            ExternalLink::firstOrCreate(
                ['title' => $linkData['title']],
                [
                    'title' => $linkData['title'],
                    'url' => $linkData['url'],
                    'category' => $linkData['category'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        // 8. Create Page (Profil)
        Page::firstOrCreate(
            ['slug' => 'profil-kantor'],
            [
                'title' => 'Profil Kantor Kemenag Kabupaten Nganjuk',
                'slug' => 'profil-kantor',
                'content' => '<p>Kantor Kementerian Agama Kabupaten Nganjuk merupakan instansi pemerintah yang melaksanakan tugas dan fungsi di bidang keagamaan di tingkat kabupaten. Kami berkomitmen untuk memberikan pelayanan terbaik kepada masyarakat dalam bidang keagamaan.</p><p>Portal ini menyajikan informasi terkini mengenai kegiatan, layanan, dan berbagai programme Kementerian Agama Kabupaten Nganjuk untuk masyarakat.</p>',
                'page_type' => 'profil',
                'author_id' => $admin->id,
                'published_at' => now(),
            ]
        );

        // 9. Create Header Menu
        $headerMenu = Menu::firstOrCreate(
            ['name' => 'Main Menu', 'location' => 'header'],
            ['name' => 'Main Menu', 'location' => 'header']
        );

        $headerMenuItems = [
            ['title' => 'Profil', 'url' => '/profil', 'sort_order' => 1],
            ['title' => 'Berita', 'url' => '/berita', 'sort_order' => 2],
            ['title' => 'Layanan', 'url' => '#', 'sort_order' => 3, 'children' => [
                ['title' => 'Pendaftaran Haji', 'url' => '/haji'],
                ['title' => 'Nikah', 'url' => '/nikah'],
                ['title' => 'Sertifikasi Halal', 'url' => '/halal'],
            ]],
            ['title' => 'Unduh', 'url' => '/download', 'sort_order' => 4],
            ['title' => 'Kontak', 'url' => '/kontak', 'sort_order' => 5],
        ];

        foreach ($headerMenuItems as $item) {
            $children = $item['children'] ?? null;
            unset($item['children']);
            $item['menu_id'] = $headerMenu->id;
            $item['is_active'] = true;

            $menuItem = MenuItem::firstOrCreate(
                ['menu_id' => $headerMenu->id, 'title' => $item['title']],
                $item
            );

            // Create children if exists
            if ($children) {
                foreach ($children as $childIndex => $child) {
                    MenuItem::firstOrCreate(
                        ['menu_id' => $headerMenu->id, 'parent_id' => $menuItem->id, 'title' => $child['title']],
                        [
                            'title' => $child['title'],
                            'url' => $child['url'],
                            'menu_id' => $headerMenu->id,
                            'parent_id' => $menuItem->id,
                            'is_active' => true,
                            'sort_order' => $childIndex + 1,
                        ]
                    );
                }
            }
        }

        // 10. Create Footer Menu
        $footerMenu = Menu::firstOrCreate(
            ['name' => 'Footer Menu', 'location' => 'footer'],
            ['name' => 'Footer Menu', 'location' => 'footer']
        );

        $footerMenuItems = [
            ['title' => 'Tentang Kami', 'url' => '/profil', 'sort_order' => 1],
            ['title' => 'Struktur Organisasi', 'url' => '/struktur', 'sort_order' => 2],
            ['title' => 'Visi Misi', 'url' => '/visi-misi', 'sort_order' => 3],
            ['title' => 'Tugas Pokok', 'url' => '/tupoksi', 'sort_order' => 4],
            ['title' => 'Berita', 'url' => '/berita', 'sort_order' => 5],
            ['title' => 'Agenda', 'url' => '/agenda', 'sort_order' => 6],
        ];

        foreach ($footerMenuItems as $item) {
            MenuItem::firstOrCreate(
                ['menu_id' => $footerMenu->id, 'title' => $item['title']],
                [
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'menu_id' => $footerMenu->id,
                    'is_active' => true,
                    'sort_order' => $item['sort_order'],
                ]
            );
        }

        $this->command->info('Homepage seeder completed successfully!');
    }
}
