<?php

namespace Database\Seeders;

use App\Models\Tenant\IssueSeverity;
use App\Models\Tenant\IssueStatus;
use App\Models\Tenant\IssueType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       IssueType::insert([
            [
            'name' => 'Bug',           
            ],
            [
            'name' => 'NewFeature',           
            ],
            [
            'name' => 'Task',            
            ],
            [
                'name' => 'Improvment'
            ]
        ]);


         IssueStatus::insert([
            [
            'name' => 'open',           
            ],
            [
            'name' => 'pending',           
            ],
            [
            'name' => 'resolved',            
            ],
            [
                'name' => 'closed'
            ],
             [
                'name' => 'reopned'
            ]
        ]);

          IssueSeverity::insert([
            [
            'name' => 'Major',           
            ],
            [
            'name' => 'Blocker',           
            ],
            [
            'name' => 'Minor',            
            ],
            [
                'name' => 'Trivil'
            ],
             [
                'name' => 'Critical'
            ]
        ]);
    }
}
