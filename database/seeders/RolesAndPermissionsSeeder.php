<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
   public function run(): void
{
    $teacherPermissions = [
        'view_classrooms',
        'view_students',
        'manage_attendance',
        'add_attachments',
        'add_notes',
        'view_any_post',
    ];

    $adminPermissions = [
        ...$teacherPermissions,
        'view_any_classroom',
        'view_any_student',
        'view_any_guardian',
        'view_any_role',
        'view_any_permission',
        'view_any_admin',
        'view_any_fee_plan',
    ];

    foreach ($adminPermissions as $permission) {
        Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
    }

    $teacherRole = Role::firstOrCreate(['name' => 'teacher', 'guard_name' => 'admin']);
    $teacherRole->syncPermissions($teacherPermissions);

    $adminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'admin']);
    $adminRole->syncPermissions(Permission::all());
}
}
