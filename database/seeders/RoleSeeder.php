<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               // Create Roles
            $admin = Role::create(['name' => 'admin', 'guard_name' => 'tenant']);
            $manager = Role::create(['name' => 'manager', 'guard_name' => 'tenant' ]);
            $user = Role::create(['name' => 'user', 'guard_name' => 'tenant']);

        // Create Permissions
        $permissions = [
            'add',
            'edit',
            'delete',
            'view',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'tenant']);
        }

        // Assign Permissions to Roles
        $admin->givePermissionTo($permissions);
        $manager->givePermissionTo(['add', 'edit']);
        $user->givePermissionTo(['add','view']);

    }
}
