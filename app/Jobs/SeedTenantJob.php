<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;
use App\Models\Tenant\Project;
use App\Models\Tenant\User;

class SeedTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $tenant;
    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tenant = $this->tenant->run(function (){
           $user = User::create([
                'name' => $this->tenant->name,
                'email' => $this->tenant->email,
                'password' => $this->tenant->password,
                'phone' => $this->tenant->phone,
                'is_super_admin' => 1,
                'status' => 0,
            ]);
            //assigne project also here
            $projects = Project::all();

            foreach ($projects as $project) {
                $project->users()->attach(1, [
                    'is_lead' => 1,
                    'role_id' => 1
                ]);
            }
            
            //$user->projects()->attach(1);
        });
    }
}
