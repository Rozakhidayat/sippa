<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate([
            'name' => 'Manajer TI'
        ],
        ['name' => 'Manajer TI']
        );

        Role::updateOrCreate([
            'name' => 'SVP'
        ],
        ['name' => 'SVP']
        );

        Role::updateOrCreate([
            'name' => 'VP'
        ],
        ['name' => 'VP']
        );

        Role::updateOrCreate([
            'name' => 'Staff'
        ],
        ['name' => 'Staff']
        );

        Role::updateOrCreate([
            'name' => 'Business Partner'
        ],
        ['name' => 'Business Partner']
        );

        Role::updateOrCreate([
            'name' => 'Enterprise Architect'
        ],
        ['name' => 'Enterprise Architect']
        );
    }
}
