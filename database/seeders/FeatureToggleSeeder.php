<?php

namespace Database\Seeders;

use App\Models\FeatureToggle;
use Illuminate\Database\Seeder;

class FeatureToggleSeeder extends Seeder
{
    public function run(): void
    {
        $features = [
            [
                'key' => 'dashboard',
                'name' => 'Dashboard',
                'description' => 'Halaman dashboard untuk admin dan superadmin',
                'enabled' => true,
            ],
            [
                'key' => 'data-barang',
                'name' => 'Data Barang',
                'description' => 'Fitur untuk mengelola data barang (CRUD)',
                'enabled' => true,
            ],
            [
                'key' => 'kategori-barang',
                'name' => 'Kategori Barang',
                'description' => 'Fitur untuk mengelola kategori barang',
                'enabled' => true,
            ],
            [
                'key' => 'laporan-barang',
                'name' => 'Laporan Barang',
                'description' => 'Fitur untuk melihat laporan barang',
                'enabled' => true,
            ],
        ];

        foreach ($features as $feature) {
            FeatureToggle::updateOrCreate(
                ['key' => $feature['key']],
                $feature
            );
        }
    }
}
