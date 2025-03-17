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
            $admin = Role::create(['name' => 'admin']);
            $manager = Role::create(['name' => 'manager']);
            $user = Role::create(['name' => 'user']);

        // Create Permissions
        $permissions = [
            'add',
            'edit',
            'delete',
            'view',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $admin->givePermissionTo($permissions);
        $manager->givePermissionTo(['add', 'edit']);
        $user->givePermissionTo(['add','view']);

    }
}
