<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = Admin::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('123456'),
                'type'     => 'super_admin',
            ]
        );
        if (!$admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }
    }
}
