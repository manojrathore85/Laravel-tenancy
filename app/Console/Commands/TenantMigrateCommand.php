<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {tenant_id} {migrations_path?} {force?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for a specific tenant specific migration path with force';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantId = $this->argument('tenant_id');
        $migrationsPath = $this->argument('migrations_path');

        // Find the tenant      
        $tenant = Tenant::find($tenantId);
        if (!$tenant) {
            $this->error("Tenant with ID {$tenantId} not found.");
            return;
        }

        // Switch to the tenant's database
        tenancy()->initialize($tenant);   
        $this->info("Switched to Tenant ID: ");
        // Log the current tenant database
        // Log::info('Current Tenant Database:', ['database' => config('database.connections.tenant.database')]);
        $this->info("Using Tenant Database: " . config('database.connections.tenant.database'));


        //chek if migration path is provided and provided migration avilable or not on the path
        if ($migrationsPath) {
            if (!file_exists($migrationsPath)) {
                $this->error("Migration path {$migrationsPath} does not exist.");
                return;
            }   
        }

    // Run the migration for the specific tenant
    $tenant->run(function () use ($tenantId, $migrationsPath) {
        $options = [
            '--database' => 'tenant',
            '--force' => true, // Force migration in production
        ];

        if ($migrationsPath) {
           // $options['--path'] = str_replace(base_path() . '/', '', $migrationsPath); // Relative path
            $options['--path'] = $migrationsPath; // Ensure it's relative
        }
        

        Artisan::call('migrate:refresh', [
            '--database' => $tenantId,
            '--path' => $migrationsPath,
            '--force' => true
        ]);
    });

    $this->info("Migrations have been run for Tenant ID: {$tenantId}");
    }
}
