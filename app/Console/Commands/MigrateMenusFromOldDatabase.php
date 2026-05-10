<?php

namespace App\Console\Commands;

use App\Models\MenuItem;
use Illuminate\Console\Command;

class MigrateMenusFromOldDatabase extends Command
{
    protected $signature = 'migrate:menus
                            {--fresh : Drop all existing menu items before seeding}';

    protected $description = 'Seed default menu items for Kemenag Nganjuk portal';

    public function handle(): int
    {
        $this->info('===========================================');
        $this->info('  Seeding Menu Items');
        $this->info('===========================================');
        $this->newLine();

        if ($this->option('fresh')) {
            $this->warn('Mode fresh aktif - menghapus semua menu items...');
            MenuItem::truncate();
            $this->info('✓ Semua menu items berhasil dihapus');
        }

        $this->info('Memulai seeding menu items...');
        $this->newLine();

        $created = 0;

        // ==================== MENU UTAMA ====================
        $beranda = MenuItem::create([
            'title' => 'Beranda',
            'url' => '/',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        $profil = MenuItem::create([
            'title' => 'Profil',
            'url' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        $informasi = MenuItem::create([
            'title' => 'Informasi',
            'url' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $created++;

        $layanan = MenuItem::create([
            'title' => 'Layanan Publik',
            'url' => null,
            'sort_order' => 4,
            'is_active' => true,
        ]);
        $created++;

        $unitLembaga = MenuItem::create([
            'title' => 'Unit & Lembaga',
            'url' => null,
            'sort_order' => 5,
            'is_active' => true,
        ]);
        $created++;

        $dokumen = MenuItem::create([
            'title' => 'Dokumen',
            'url' => null,
            'sort_order' => 6,
            'is_active' => true,
        ]);
        $created++;

        $tautan = MenuItem::create([
            'title' => 'Tautan Terkait',
            'url' => null,
            'sort_order' => 7,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Profil ====================
        MenuItem::create([
            'parent_id' => $profil->id,
            'title' => 'Profil',
            'url' => '/profil',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $profil->id,
            'title' => 'Tentang Kemenag Nganjuk',
            'url' => '/profil/tentang',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Informasi ====================
        MenuItem::create([
            'parent_id' => $informasi->id,
            'title' => 'Semua Berita',
            'url' => '/berita',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $informasi->id,
            'title' => 'Pengumuman',
            'url' => '/pengumuman',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $informasi->id,
            'title' => 'Download',
            'url' => '/download',
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $informasi->id,
            'title' => 'Agenda Kegiatan',
            'url' => '/agenda',
            'sort_order' => 4,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Layanan Publik ====================
        MenuItem::create([
            'parent_id' => $layanan->id,
            'title' => 'PPID',
            'url' => '/ppid',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $layanan->id,
            'title' => 'SKM',
            'url' => '/skm',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $layanan->id,
            'title' => 'Lapor',
            'url' => '/lapor',
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Unit & Lembaga ====================
        $unitPelayanan = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'Unit Pelayanan',
            'url' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        $man = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'MAN',
            'url' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        $mtsn = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'MTsN',
            'url' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $created++;

        $min = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'MIN',
            'url' => null,
            'sort_order' => 4,
            'is_active' => true,
        ]);
        $created++;

        $kua = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'KUA',
            'url' => null,
            'sort_order' => 5,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'Jurnal Pengawas PAI',
            'url' => '/jurnal-pengawas',
            'sort_order' => 6,
            'is_active' => true,
        ]);
        $created++;

        $lembaga = MenuItem::create([
            'parent_id' => $unitLembaga->id,
            'title' => 'Lembaga',
            'url' => null,
            'sort_order' => 7,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUB-SUBMENU: Unit Pelayanan ====================
        $unitPelayananItems = [
            'SUB BAG TU',
            'SEKSI PENDIDIKAN MADRASAH',
            'SEKSI BIMAS ISLAM',
            'SEKSI HAJI & UMRAH',
            'SEKSI PENDIDIKAN AGAMA ISLAM',
            'PENYELENGGARA ZAKAT WAKAF',
            'PD. PONTREN',
            'KEPEGAWAIAN',
        ];

        foreach ($unitPelayananItems as $i => $title) {
            MenuItem::create([
                'parent_id' => $unitPelayanan->id,
                'title' => $title,
                'url' => '/unit-pelayanan/' . strtolower(str_replace(' ', '-', $title)),
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $created++;
        }

        // ==================== SUB-SUBMENU: MAN ====================
        $manItems = [
            ['title' => 'MAN 1 NGANJUK', 'url' => 'https://man1nganjuk.sch.id/'],
            ['title' => 'MAN 3 NGANJUK', 'url' => 'https://man3nganjuk.sch.id/'],
            ['title' => 'MAN 2 NGANJUK', 'url' => 'https://man2nganjuk.sch.id/'],
        ];

        foreach ($manItems as $i => $item) {
            MenuItem::create([
                'parent_id' => $man->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $created++;
        }

        // ==================== SUB-SUBMENU: MTsN ====================
        $mtsnItems = [
            ['title' => 'MTsN 1 NGANJUK', 'url' => 'https://www.mtsn1nganjuk.sch.id/'],
            ['title' => 'MTsN 2 NGANJUK', 'url' => 'https://www.mtsn2nganjuk.sch.id/'],
            ['title' => 'MTsN 3 NGANJUK', 'url' => 'https://www.mtsn3nganjuk.sch.id/'],
            ['title' => 'MTsN 4 NGANJUK', 'url' => null],
            ['title' => 'MTsN 5 NGANJUK', 'url' => 'https://www.mtsnnganjuk.sch.id/'],
            ['title' => 'MTsN 6 NGANJUK', 'url' => null],
            ['title' => 'MTsN 7 NGANJUK', 'url' => null],
            ['title' => 'MTsN 8 NGANJUK', 'url' => null],
            ['title' => 'MTsN 9 NGANJUK', 'url' => null],
            ['title' => 'MTsN 10 NGANJUK', 'url' => null],
        ];

        foreach ($mtsnItems as $i => $item) {
            MenuItem::create([
                'parent_id' => $mtsn->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $created++;
        }

        // ==================== SUB-SUBMENU: MIN ====================
        $minItems = [
            ['title' => 'MIN 1 NGANJUK', 'url' => null],
            ['title' => 'MIN 2 NGANJUK', 'url' => 'http://www.min2nganjuk.sch.id/madrasah/'],
            ['title' => 'MIN 3 NGANJUK', 'url' => null],
            ['title' => 'MIN 4 NGANJUK', 'url' => null],
            ['title' => 'MIN 5 NGANJUK', 'url' => null],
            ['title' => 'MIN 6 NGANJUK', 'url' => null],
            ['title' => 'MIN 7 NGANJUK', 'url' => null],
            ['title' => 'MIN 8 NGANJUK', 'url' => null],
            ['title' => 'MIN 9 NGANJUK', 'url' => null],
            ['title' => 'MIN 10 NGANJUK', 'url' => null],
            ['title' => 'MIN 11 NGANJUK', 'url' => null],
        ];

        foreach ($minItems as $i => $item) {
            MenuItem::create([
                'parent_id' => $min->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $created++;
        }

        // ==================== SUB-SUBMENU: KUA ====================
        $kuaItems = [
            ['title' => 'KUA JATIKALEN', 'url' => 'https://kuajatikalen.kemenagnganjuk.id'],
            ['title' => 'KUA NGANJUK', 'url' => 'https://kuanganjuk.kemenagnganjuk.id'],
            ['title' => 'KUA SUKOMORO', 'url' => 'https://kuasukomoro.kemenagnganjuk.id'],
            ['title' => 'KUA BAGOR', 'url' => 'https://kuabagor.kemenagnganjuk.id'],
            ['title' => 'KUA WILANGAN', 'url' => 'https://kuawilangan.kemenagnganjuk.id'],
            ['title' => 'KUA BERBEK', 'url' => 'https://kuaberbek.kemenagnganjuk.id'],
            ['title' => 'KUA NGETOS', 'url' => 'https://kuangetos.kemenagnganjuk.id'],
            ['title' => 'KUA LOCERET', 'url' => 'https://kualoceret.kemenagnganjuk.id'],
            ['title' => 'KUA SAWAHAN', 'url' => 'https://kuasawahan.kemenagnganjuk.id'],
            ['title' => 'KUA KERTOSONO', 'url' => 'https://kuakertosono.kemenagnganjuk.id'],
            ['title' => 'KUA BARON', 'url' => 'https://kuabaron.kemenagnganjuk.id'],
            ['title' => 'KUA NGRONGGOT', 'url' => 'https://kuangronggot.kemenagnganjuk.id'],
            ['title' => 'KUA PATIANROWO', 'url' => 'https://kuapatianrowo.kemenagnganjuk.id'],
            ['title' => 'KUA TANJUNGANOM', 'url' => 'https://kuatanjunganom.kemenagnganjuk.id'],
            ['title' => 'KUA PRAMBON', 'url' => 'https://kuaprambon.kemenagnganjuk.id'],
            ['title' => 'KUA PACE', 'url' => 'https://kuapace.kemenagnganjuk.id'],
            ['title' => 'KUA LENGKONG', 'url' => 'https://kualengkong.kemenagnganjuk.id'],
            ['title' => 'KUA GONDANG', 'url' => 'https://kuagondang.kemenagnganjuk.id'],
            ['title' => 'KUA NGLUYU', 'url' => 'https://kuangluyu.kemenagnganjuk.id'],
            ['title' => 'KUA REJOSO', 'url' => 'https://kuarejoso.kemenagnganjuk.id'],
        ];

        foreach ($kuaItems as $i => $item) {
            MenuItem::create([
                'parent_id' => $kua->id,
                'title' => $item['title'],
                'url' => $item['url'],
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
            $created++;
        }

        // ==================== SUB-SUBMENU: Lembaga ====================
        MenuItem::create([
            'parent_id' => $lembaga->id,
            'title' => 'Daftar Lembaga',
            'url' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Dokumen ====================
        MenuItem::create([
            'parent_id' => $dokumen->id,
            'title' => 'Regulasi dan Info Penting',
            'url' => '/dokumen/regulasi',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        // ==================== SUBMENU: Tautan Terkait ====================
        MenuItem::create([
            'parent_id' => $tautan->id,
            'title' => 'Kanwil Kemenag Jatim',
            'url' => 'https://jatim.kemenag.go.id',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $tautan->id,
            'title' => 'Haji dan Umrah',
            'url' => 'https://haji.kemenag.go.id',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        $created++;

        MenuItem::create([
            'parent_id' => $tautan->id,
            'title' => 'Pernikahan',
            'url' => '/pernikahan',
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $created++;

        $this->newLine();
        $this->info("✓ Berhasil membuat {$created} menu items");
        $this->info("  Total menu items sekarang: " . MenuItem::count());
        $this->newLine();

        return self::SUCCESS;
    }
}
