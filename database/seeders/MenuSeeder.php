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
                    'name' => 'Master',
                    'route' => 'master',
                    'component' => 'master',
                    'icon' => 'HiInbox',
                    'parent_id' => null, // Use null instead of empty string for better DB compatibility
                    'sort_order' => 1,
                    'drawer' => 1,
                    'main_menu' => 1
                ],
                [
                    'name' => 'User',
                    'route' => 'users',
                    'component' => 'TenantUser',
                    'icon' => 'user-icon',
                    'parent_id' => 1, // Use null instead of empty string for better DB compatibility
                    'sort_order' => 1,
                    'drawer' => 1,
                    'main_menu' => 1
                ],
                [
                    'name' => 'Permission',
                    'route' => 'menu-permission',
                    'component' => 'Permission',
                    'icon' => 'permission-icon',
                    'parent_id' => 1,
                    'sort_order' => 2,
                    'drawer' => 1,
                    'main_menu' => 1
                ],
                [
                    'name' => 'Project',
                    'route' => 'project',
                    'component' => 'Project',
                    'icon' => 'project-icon',
                    'parent_id' => 1,
                    'sort_order' => 3,
                    'drawer' => 1,
                    'main_menu' => 1
                ],
                [
                    'name' => 'Issues',
                    'route' => 'issues',
                    'component' => 'Issues',
                    'icon' => 'HiTicket',
                    'parent_id' => 1,
                    'sort_order' => 4,
                    'drawer' => 1,
                    'main_menu' => 1
                ],
                [
                    'name' => 'Comments',
                    'route' => 'comments',
                    'component' => 'comments-icon',
                    'icon' => 'HiTicket',
                    'parent_id' => 1,
                    'sort_order' => 4,
                    'drawer' => 0,
                    'main_menu' => 0
                ],
            ]);
    }
}
