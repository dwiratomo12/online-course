<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = ['teacher', 'student'];
        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create(['name' => $role]);
        }

        // Create permissions
        $permissions = ['view courses', 'create courses', 'edit courses', 'delete courses'];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $teacherRole = \Spatie\Permission\Models\Role::findByName('teacher');
        $studentRole = \Spatie\Permission\Models\Role::findByName('student');

        //add permissions to admin and employee roles
        foreach ($permissions as $permission) {
            $teacherRole->givePermissionTo($permission);
            if ($permission !== 'delete') {
                $studentRole->givePermissionTo($permission);
            }
        }

        //membuat data super admin
        $user = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin'),
        ]);

        //assign role to user super admin
        $user->assignRole('teacher');
    }
}
