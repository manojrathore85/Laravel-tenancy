<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Super Admin',
        //     'email' => 'superadmin@abc.com',
        // ]);
        $this->call(
            [
                RoleSeeder::class,
                MenuSeeder::class,
            ]
        );
    }
}
