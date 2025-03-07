<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run()
    {
        // إنشاء الصلاحيات
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'view courses']);
        Permission::create(['name' => 'edit courses']);

        // إنشاء الأدوار
        $admin = Role::create(['name' => 'admin']);
        $teacher = Role::create(['name' => 'teacher']);
        $student = Role::create(['name' => 'student']);

        // إسناد الصلاحيات للأدوار
        $admin->givePermissionTo(['manage users', 'view courses', 'edit courses']);
        $teacher->givePermissionTo(['view courses', 'edit courses']);
        $student->givePermissionTo(['view courses']);
    }

}
