<?php
// run this rile like this 
// php run_tenant_migration.php d2cfd2c7-54a4-4a9f-a300-909379de1cc9 2025_04_17_173258_create_issues_table.php
// php run_tenant_migration.php TENANT_UUID MIGRATION_FILENAME
use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$tenantId = $argv[1] ?? null;
$migrationFile = $argv[2] ?? null;

if (!$tenantId || !$migrationFile) {
    echo "Usage: php run_tenant_migration.php TENANT_UUID MIGRATION_FILENAME\n";
    exit(1);
}

$tenant = \App\Models\Tenant::find($tenantId);

if ($tenant) {
    tenancy()->initialize($tenant);

    \Artisan::call('migrate', [
        '--path' => "database/migrations/tenant/{$migrationFile}",
        '--force' => true,
    ]);

    echo \Artisan::output();
} else {
    echo "Tenant not found!\n";
}
