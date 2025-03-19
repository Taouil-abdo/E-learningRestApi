<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage courses']);
        Permission::create(['name' => 'enroll in courses']);

        $admin = Role::create(['name' => 'admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $student = Role::create(['name' => 'student']);

        $admin->givePermissionTo(['manage users', 'manage courses']);
        $teacher->givePermissionTo(['manage courses']);
        $student->givePermissionTo(['enroll in courses']);
    }
}
