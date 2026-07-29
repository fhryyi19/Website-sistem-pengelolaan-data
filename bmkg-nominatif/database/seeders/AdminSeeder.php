<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator — memiliki akses penuh ke seluruh fitur aplikasi.']
        );

        User::updateOrCreate(
            ['username' => 'Faell'],
            [
                'role_id'    => $adminRole->id,
                'name'       => 'M. Farel Alkhansyah',
                'email'      => 'm.farelalkhansyah@gmail.com',
                'username'   => 'Faell',
                'password'   => Hash::make('......'),
                'is_active'  => true,
            ]
        );
    }
}
