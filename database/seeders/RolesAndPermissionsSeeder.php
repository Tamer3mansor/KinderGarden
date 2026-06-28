<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    // Permissions
    $permissions = [
        'view_classrooms',
        'view_students',
        'manage_attendance',
        'add_attachments',
        'add_notes',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
    }

    // Roles
    $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'admin']);
    $teacherRole->givePermissionTo($permissions);

    $adminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);
    $adminRole->givePermissionTo(Permission::all());
}
}
