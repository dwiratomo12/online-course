<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Create permissions
        $permissions = ['view courses', 'create courses', 'edit courses', 'delete courses'];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $teacherRole = Role::create(['name' => 'teacher']);
        $studentRole = Role::create(['name' => 'student']);

        //add permissions to admin and employee roles
        foreach ($permissions as $permission) {
            $teacherRole->givePermissionTo($permission);
            if ($permission !== 'delete') {
                $studentRole->givePermissionTo($permission);
            }
        }

        //membuat data super admin
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin'),
        ]);

        //assign role to user super admin
        $user->assignRole('teacher');
    }
}
