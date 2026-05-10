-- =====================================================
-- FIX: Alter menu_items table untuk single-table hierarchy
-- Run ini untuk fix error "Field 'menu_id' doesn't have a default value"
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Drop foreign key dan kolom menu_id jika ada
ALTER TABLE menu_items DROP FOREIGN KEY IF EXISTS menu_items_menu_id_foreign;
ALTER TABLE menu_items DROP COLUMN IF EXISTS menu_id;

-- Pastikan kolom parent_id ada dengan constraint yang benar
ALTER TABLE menu_items ADD COLUMN IF NOT EXISTS parent_id BIGINT UNSIGNED NULL AFTER id;
ALTER TABLE menu_items ADD CONSTRAINT IF NOT EXISTS fk_menu_items_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- Rekam: Struktur tabel sekarang
-- =====================================================
DESCRIBE menu_items;
