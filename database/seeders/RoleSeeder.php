<?php

namespace Database\Seeders;

use App\Models\Tenant\RoleMenuPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;

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
            $menus = Menu::all();
            // Assigne All menu permission to admin
            foreach ($menus as $menu) {
                RoleMenuPermission::create([
                    'role_id' => $admin->id,
                    'menu_id' => $menu->id,
                    'can_add' => true,
                    'can_edit' => true,
                    'can_delete' => true,
                    'can_view' => true,
                    
                ]);
            }  
            //Role Menu Permissions Menu id the order of menu seeding 
            RoleMenuPermission::create([
                'role_id' => $admin->id,
                'menu_id' => 1,
                'can_add' => true,
                'can_edit' => true,
                'can_delete' => true,
                'can_view' => true,
                
            ]);
            RoleMenuPermission::create([
                'role_id' => $admin->id,
                'menu_id' => 2,
                'can_add' => true,
                'can_edit' => true,
                'can_delete' => true,
                'can_view' => true,
                
            ]);
            RoleMenuPermission::create([
                'role_id' => $manager->id,
                'menu_id' => 1,
                'can_add' => true,
                'can_edit' => false,
                'can_delete' => false,
                'can_view' => true,
                
            ]);
  


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
