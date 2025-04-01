<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
               // Create Roles
               Menu::insert([
                [
                    'name' => 'User',
                    'route' => 'users',
                    'component' => 'TenantUser',
                    'icon' => 'user-icon',
                    'parent_id' => null, // Use null instead of empty string for better DB compatibility
                    'sort_order' => 1
                ],
                [
                    'name' => 'Permission',
                    'route' => 'menu-permission',
                    'component' => 'Permission',
                    'icon' => 'permission-icon',
                    'parent_id' => null,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Project',
                    'route' => 'project',
                    'component' => 'Project',
                    'icon' => 'project-icon',
                    'parent_id' => null,
                    'sort_order' => 3
                ],
            ]);
    }
}
