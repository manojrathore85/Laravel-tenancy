<?php

namespace Database\Seeders;

use App\Models\Tenant\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::insert([
            [
            'name' => 'Project 1',
            'code' => 'P1',
            'status' => 'active',
            ],
            [
            'name' => 'Project 2',
            'code' => 'P2',
            'status' => 'active',
            ],
            [
            'name' => 'Project 3',
            'code' => 'P3',
            'status' => 'active',
            ]
        ]);

    }
}
