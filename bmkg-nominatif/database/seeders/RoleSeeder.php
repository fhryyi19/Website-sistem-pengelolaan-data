<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'        => 'admin',
                'description' => 'Administrator — memiliki akses penuh ke seluruh fitur aplikasi.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'name'        => 'user',
                'description' => 'User — hanya dapat melihat data (read-only).',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ];

        DB::table('roles')->insertOrIgnore($roles);
    }
}
