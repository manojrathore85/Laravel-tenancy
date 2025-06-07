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
            'code' => 'P101',
            'description' => 'project1 description',            
            'status' => 0,
            ],
            [
            'name' => 'Project 2',
            'code' => 'P102',
             'description' => 'project1 description',
            'status' => 0,
            ],
            [
            'name' => 'Project 3',
            'code' => 'P103',
             'description' => 'project1 description',
            'status' => 0,
            ]
        ]);

    }
}
