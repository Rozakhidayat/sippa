<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [
            // --- COST LEADERSHIP (Periode Baru & Lama) ---
            ['category' => 'Cost Leadership', 'item' => 'Digital Sistem Perpajakan', 'periode' => '2020 - 2026'],
            ['category' => 'Cost Leadership', 'item' => 'Digital Network Distribution', 'periode' => '2020 - 2024'],
            ['category' => 'Cost Leadership', 'item' => 'Procurement Excellence', 'periode' => '2020 - 2026'],
            ['category' => 'Cost Leadership', 'item' => 'Business Process Digitalization - Financial Management', 'periode' => '2020 - 2026'],
            ['category' => 'Cost Leadership', 'item' => 'Business Process Digitalization - GRC', 'periode' => '2024 - 2026'],

            // --- NEW REVENUE (Update ke 2026) ---
            ['category' => 'New Revenue', 'item' => 'Big Data & Advance Analytics', 'periode' => '2020 - 2026'],
            ['category' => 'New Revenue', 'item' => 'Sistem Prediksi Harga', 'periode' => '2024 - 2026'],
            ['category' => 'New Revenue', 'item' => 'Agrosolusi', 'periode' => '2020 - 2026'],
            ['category' => 'New Revenue', 'item' => 'Retail Management System', 'periode' => '2025 - 2026'],
            ['category' => 'New Revenue', 'item' => 'Commercial Marketing', 'periode' => '2024 - 2026'],

            // --- WORLD CLASS COMPANY (Update ke 2026) ---
            ['category' => 'World Class Company', 'item' => 'Performance Management', 'periode' => '2020 - 2026'],
            ['category' => 'World Class Company', 'item' => 'Learning Development Management System (LDMS)', 'periode' => '2020 - 2026'],
            ['category' => 'World Class Company', 'item' => 'Talent Management System', 'periode' => '2024 - 2026'],
            ['category' => 'World Class Company', 'item' => 'ERP Optimization - Modul/Feature Optimization', 'periode' => '2020 - 2026'],
            ['category' => 'World Class Company', 'item' => 'Forward Looking Strategy', 'periode' => '2025 - 2026'],
        ];

        foreach ($data as $val) {
            Theme::updateOrCreate(
                [
                    'category' => $val['category'],
                    'item'     => $val['item']
                ],
                [
                    'periode'   => $val['periode'],
                    'is_active' => true,
                ]
            );
        }
    }
}
