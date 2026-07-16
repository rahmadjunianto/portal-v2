<?php

namespace Database\Seeders;

use App\Models\KnowledgeBank;
use Illuminate\Database\Seeder;

class KnowledgeBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Layanan Nikah
            [
                'question' => 'Bagaimana cara daftar nikah?',
                'answer' => 'Pendaftaran nikah di Kantor Kemenag Nganjuk:\n\n1. Siapkan dokumen: KTP, KK, Akta Lahir, Pas Foto\n2. Datang ke Kantor Kemenag Kabupaten Nganjuk\n3. Isi formulir pendaftaran\n4. Survey oleh petugas\n5. Akad nikah\n\nInformasi lebih lanjut: (0358) 321085',
                'category' => 'nikah',
                'tags' => 'nikah, daftar, pernikahan, izin nikah',
                'priority' => 50,
                'is_active' => true,
            ],
            [
                'question' => 'Syarat nikah apa saja?',
                'answer' => 'Syarat pernikahan di Kantor Kemenag:\n\n1. Fotokopi KTP kedua calon pengantin\n2. Fotokopi Kartu Keluarga (KK)\n3. Akta Kelahiran\n4. Pas Foto 3x4 (2 lembar)\n5. Surat izin orang tua (bagi usia di bawah 21 tahun)\n6. Surat keterangan sehat dari dokter\n\nHubungi kami untuk informasi lengkap.',
                'category' => 'nikah',
                'tags' => 'syarat, nikah, dokumen, pernikahan',
                'priority' => 45,
                'is_active' => true,
            ],
            // Zakat
            [
                'question' => 'Bagaimana cara bayar zakat fitrah?',
                'answer' => 'Pembayaran Zakat Fitrah:\n\n📦 Bisa berupa:\n- Beras: 2,5 kg\n- Uang: setara 2,5 kg beras\n\n📍 Lokasi:\n- Kantor Kemenag Kabupaten Nganjuk\n- Desa/Kelurahan masing-masing\n\n📅 Waktu:\n- H-7 sampai H+1 Lebaran Idulfitri\n\nHubungi: (0358) 321085',
                'category' => 'zakat',
                'tags' => 'zakat, fitrah, bayar, infak',
                'priority' => 50,
                'is_active' => true,
            ],
            // Pendidikan
            [
                'question' => 'Bagaimana cara daftar MTs?',
                'answer' => 'Pendaftaran MTs (Madrasah Tsanawiyah):\n\n1. Usia minimal 12 tahun\n2. Lulusan MI atau SD kelas 6\n3. Siapkan: Akta Lahir, Kartu Keluarga, Ijazah/SKHUN\n4. Daftar di MTs terdekat atau Kantor Kemenag\n\n📞 Info lebih lanjut: (0358) 321085',
                'category' => 'pendidikan',
                'tags' => 'mts, madrasah, daftar, sekolah',
                'priority' => 40,
                'is_active' => true,
            ],
            // Produk Halal
            [
                'question' => 'Cara urus sertifikasi halal?',
                'answer' => 'Sertifikasi Halal:\n\n1. Kunjungi BPJPH atau akses https://halal.go.id\n2. Siapkan dokumen produk\n3. Ajukan permohonan\n4. Audit oleh Auditor Halal\n5. Sertifikat halal diterbitkan\n\n📞 Konsultasi: (0358) 321085',
                'category' => 'halal',
                'tags' => 'halal, sertifikasi, sertifikat, produk',
                'priority' => 45,
                'is_active' => true,
            ],
            // Jam Operasional
            [
                'question' => 'Jam pelayanan apa saja?',
                'answer' => '📅 Jam Pelayanan Kantor Kemenag Nganjuk:\n\n🕐 Senin - Kamis: 08.00 - 15.30 WIB\n🕐 Jumat: 08.00 - 16.00 WIB\n🗓️ Sabtu - Minggu: LIBUR\n\n📞 Telepon: (0358) 321085\n📧 Email: kantor@nganjuk.kemenag.go.id',
                'category' => 'umum',
                'tags' => 'jam, pelayanan, buka, jam buka',
                'priority' => 60,
                'is_active' => true,
            ],
            // Alamat
            [
                'question' => 'Alamat kantor?',
                'answer' => '📍 Alamat Kantor Kemenag Nganjuk:\n\nKantor Kementerian Agama Kabupaten Nganjuk\nJl. Ahmad Yani No. 17, Nganjuk\nJawa Timur\n\n📞 (0358) 321085\n📧 kantor@nganjuk.kemenag.go.id\n🌐 https://nganjuk.kemenag.go.id',
                'category' => 'umum',
                'tags' => 'alamat, lokasi, kantor, dimana',
                'priority' => 55,
                'is_active' => true,
            ],
        ];

        foreach ($data as $item) {
            KnowledgeBank::create($item);
        }
    }
}
