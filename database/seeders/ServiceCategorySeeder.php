<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kepegawaian', 'icon' => 'fas fa-user-tie', 'color' => '#007bff', 'sort_order' => 1, 'is_active' => true],
            ['name' => 'Umum & FKUB', 'icon' => 'fas fa-building', 'color' => '#28a745', 'sort_order' => 2, 'is_active' => true],
            ['name' => 'Pendidikan Madrasah', 'icon' => 'fas fa-school', 'color' => '#17a2b8', 'sort_order' => 3, 'is_active' => true],
            ['name' => 'Pendidikan Diniyah dan Pondok Pesantren', 'icon' => 'fas fa-mosque', 'color' => '#ffc107', 'sort_order' => 4, 'is_active' => true],
            ['name' => 'Pendidikan Agama Islam', 'icon' => 'fas fa-quran', 'color' => '#6610f2', 'sort_order' => 5, 'is_active' => true],
            ['name' => 'Bimbingan Masyarakat Islam', 'icon' => 'fas fa-hands-helping', 'color' => '#e83e8c', 'sort_order' => 6, 'is_active' => true],
            ['name' => 'Zakat dan Wakaf', 'icon' => 'fas fa-hand-holding-heart', 'color' => '#fd7e14', 'sort_order' => 7, 'is_active' => true],
            ['name' => 'Kearsipan', 'icon' => 'fas fa-archive', 'color' => '#6c757d', 'sort_order' => 8, 'is_active' => true],
            ['name' => 'Pembinaan Agama Kristen', 'icon' => 'fas fa-cross', 'color' => '#20c997', 'sort_order' => 9, 'is_active' => true],
            ['name' => 'Kehumasan', 'icon' => 'fas fa-bullhorn', 'color' => '#dc3545', 'sort_order' => 10, 'is_active' => true],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}
