-- =====================================================
-- RESET TABLE: Drop old tables first
-- =====================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS menus;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- CREATE TABLE: menu_items with parent-child hierarchy
-- =====================================================
CREATE TABLE menu_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id BIGINT UNSIGNED NULL,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(500) NULL,
    sort_order INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_menu_items_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE SET NULL,
    INDEX idx_parent_id (parent_id),
    INDEX idx_sort_order (sort_order),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- MENU UTAMA (parent_id = NULL)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(1, NULL, 'Beranda', '/', 1, 1),
(2, NULL, 'Profil', NULL, 2, 1),
(3, NULL, 'Informasi', NULL, 3, 1),
(4, NULL, 'Layanan Publik', NULL, 4, 1),
(5, NULL, 'Unit & Lembaga', NULL, 5, 1),
(6, NULL, 'Dokumen', NULL, 6, 1),
(7, NULL, 'Tautan Terkait', NULL, 7, 1);

-- =====================================================
-- SUBMENU: Profil (parent_id = 2)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(10, 2, 'Profil', '/profil', 1, 1),
(11, 2, 'Tentang Kemenag Nganjuk', '/profil/tentang', 2, 1);

-- =====================================================
-- SUBMENU: Informasi (parent_id = 3)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(20, 3, 'Semua Berita', '/berita', 1, 1),
(21, 3, 'Pengumuman', '/pengumuman', 2, 1),
(22, 3, 'Download', '/download', 3, 1),
(23, 3, 'Agenda Kegiatan', '/agenda', 4, 1);

-- =====================================================
-- SUBMENU: Layanan Publik (parent_id = 4)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(30, 4, 'PPID', '/ppid', 1, 1),
(31, 4, 'SKM', '/skm', 2, 1),
(32, 4, 'Lapor', '/lapor', 3, 1);

-- =====================================================
-- SUBMENU: Unit & Lembaga (parent_id = 5)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
-- Kategori Unit Pelayanan
(40, 5, 'Unit Pelayanan', NULL, 1, 1),
-- Kategori MAN
(50, 5, 'MAN', NULL, 2, 1),
-- Kategori MTsN
(60, 5, 'MTsN', NULL, 3, 1),
-- Kategori MIN
(70, 5, 'MIN', NULL, 4, 1),
-- Kategori KUA
(80, 5, 'KUA', NULL, 5, 1),
-- Jurnal Pengawas PAI
(90, 5, 'Jurnal Pengawas PAI', '/jurnal-pengawas', 6, 1),
-- Kategori Lembaga
(100, 5, 'Lembaga', NULL, 7, 1);

-- =====================================================
-- SUB-SUBMENU: Unit Pelayanan (parent_id = 40)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(41, 40, 'SUB BAG TU', '/unit-pelayanan/sub-bag-tu', 1, 1),
(42, 40, 'SEKSI PENDIDIKAN MADRASAH', '/unit-pelayanan/seksi-pendidikan-madrasah', 2, 1),
(43, 40, 'SEKSI BIMAS ISLAM', '/unit-pelayanan/seksi-bimas-islam', 3, 1),
(44, 40, 'SEKSI HAJI & UMRAH', '/unit-pelayanan/seksi-haji-umrah', 4, 1),
(45, 40, 'SEKSI PENDIDIKAN AGAMA ISLAM', '/unit-pelayanan/seksi-pendidikan-agama-islam', 5, 1),
(46, 40, 'PENYELENGGARA ZAKAT WAKAF', '/unit-pelayanan/penyelenggara-zakat-wakaf', 6, 1),
(47, 40, 'PD. PONTREN', '/unit-pelayanan/pd-pontren', 7, 1),
(48, 40, 'KEPEGAWAIAN', '/unit-pelayanan/kepegawaian', 8, 1);

-- =====================================================
-- SUB-SUBMENU: MAN (parent_id = 50)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(51, 50, 'MAN 1 NGANJUK', 'https://man1nganjuk.sch.id/', 1, 1),
(52, 50, 'MAN 3 NGANJUK', 'https://man3nganjuk.sch.id/', 2, 1),
(53, 50, 'MAN 2 NGANJUK', 'https://man2nganjuk.sch.id/', 3, 1);

-- =====================================================
-- SUB-SUBMENU: MTsN (parent_id = 60)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(61, 60, 'MTsN 1 NGANJUK', 'https://www.mtsn1nganjuk.sch.id/', 1, 1),
(62, 60, 'MTsN 2 NGANJUK', 'https://www.mtsn2nganjuk.sch.id/', 2, 1),
(63, 60, 'MTsN 3 NGANJUK', 'https://www.mtsn3nganjuk.sch.id/', 3, 1),
(64, 60, 'MTsN 4 NGANJUK', NULL, 4, 1),
(65, 60, 'MTsN 5 NGANJUK', 'https://www.mtsnnganjuk.sch.id/', 5, 1),
(66, 60, 'MTsN 6 NGANJUK', NULL, 6, 1),
(67, 60, 'MTsN 7 NGANJUK', NULL, 7, 1),
(68, 60, 'MTsN 8 NGANJUK', NULL, 8, 1),
(69, 60, 'MTsN 9 NGANJUK', NULL, 9, 1),
(70, 60, 'MTsN 10 NGANJUK', NULL, 10, 1);

-- =====================================================
-- SUB-SUBMENU: MIN (parent_id = 70)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(71, 70, 'MIN 1 NGANJUK', NULL, 1, 1),
(72, 70, 'MIN 2 NGANJUK', 'http://www.min2nganjuk.sch.id/madrasah/', 2, 1),
(73, 70, 'MIN 3 NGANJUK', NULL, 3, 1),
(74, 70, 'MIN 4 NGANJUK', NULL, 4, 1),
(75, 70, 'MIN 5 NGANJUK', NULL, 5, 1),
(76, 70, 'MIN 6 NGANJUK', NULL, 6, 1),
(77, 70, 'MIN 7 NGANJUK', NULL, 7, 1),
(78, 70, 'MIN 8 NGANJUK', NULL, 8, 1),
(79, 70, 'MIN 9 NGANJUK', NULL, 9, 1),
(80, 70, 'MIN 10 NGANJUK', NULL, 10, 1),
(81, 70, 'MIN 11 NGANJUK', NULL, 11, 1);

-- =====================================================
-- SUB-SUBMENU: KUA (parent_id = 80)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(82, 80, 'KUA JATIKALEN', 'https://kuajatikalen.kemenagnganjuk.id', 1, 1),
(83, 80, 'KUA NGANJUK', 'https://kuanganjuk.kemenagnganjuk.id', 2, 1),
(84, 80, 'KUA SUKOMORO', 'https://kuasukomoro.kemenagnganjuk.id', 3, 1),
(85, 80, 'KUA BAGOR', 'https://kuabagor.kemenagnganjuk.id', 4, 1),
(86, 80, 'KUA WILANGAN', 'https://kuawilangan.kemenagnganjuk.id', 5, 1),
(87, 80, 'KUA BERBEK', 'https://kuaberbek.kemenagnganjuk.id', 6, 1),
(88, 80, 'KUA NGETOS', 'https://kuangetos.kemenagnganjuk.id', 7, 1),
(89, 80, 'KUA LOCERET', 'https://kualoceret.kemenagnganjuk.id', 8, 1),
(90, 80, 'KUA SAWAHAN', 'https://kuasawahan.kemenagnganjuk.id', 9, 1),
(91, 80, 'KUA KERTOSONO', 'https://kuakertosono.kemenagnganjuk.id', 10, 1),
(92, 80, 'KUA BARON', 'https://kuabaron.kemenagnganjuk.id', 11, 1),
(93, 80, 'KUA NGRONGGOT', 'https://kuangronggot.kemenagnganjuk.id', 12, 1),
(94, 80, 'KUA PATIANROWO', 'https://kuapatianrowo.kemenagnganjuk.id', 13, 1),
(95, 80, 'KUA TANJUNGANOM', 'https://kuatanjunganom.kemenagnganjuk.id', 14, 1),
(96, 80, 'KUA PRAMBON', 'https://kuaprambon.kemenagnganjuk.id', 15, 1),
(97, 80, 'KUA PACE', 'https://kuapace.kemenagnganjuk.id', 16, 1),
(98, 80, 'KUA LENGKONG', 'https://kualengkong.kemenagnganjuk.id', 17, 1),
(99, 80, 'KUA GONDANG', 'https://kuagondang.kemenagnganjuk.id', 18, 1),
(100, 80, 'KUA NGLUYU', 'https://kuangluyu.kemenagnganjuk.id', 19, 1),
(101, 80, 'KUA REJOSO', 'https://kuarejoso.kemenagnganjuk.id', 20, 1);

-- =====================================================
-- SUB-SUBMENU: Lembaga (parent_id = 100)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(102, 100, 'Daftar Lembaga', NULL, 1, 1);

-- =====================================================
-- SUBMENU: Dokumen (parent_id = 6)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(110, 6, 'Regulasi dan Info Penting', '/dokumen/regulasi', 1, 1);

-- =====================================================
-- SUBMENU: Tautan Terkait (parent_id = 7)
-- =====================================================
INSERT INTO menu_items (id, parent_id, title, url, sort_order, is_active) VALUES
(120, 7, 'Kanwil Kemenag Jatim', 'https://jatim.kemenag.go.id', 1, 1),
(121, 7, 'Haji dan Umrah', 'https://haji.kemenag.go.id', 2, 1),
(122, 7, 'Pernikahan', '/pernikahan', 3, 1);
