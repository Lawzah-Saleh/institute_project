<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        $admin->assignRole('admin');

        $teacher = User::create([
            'name' => 'Teacher User',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        $teacher->assignRole('teacher');

        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'password' => bcrypt('password123'),
        ]);
        $student->assignRole('student');
    }
}
