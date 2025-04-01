<!-- to run in tinker follwed this steps 
ruun PHP artisan tinker 
then run  $tenant = \App\Models\Tenant::find('d2cfd2c7-54a4-4a9f-a300-909379de1cc9');
ten you can run the any if block mention below; 
-->

$tenant = \App\Models\Tenant::find('d2cfd2c7-54a4-4a9f-a300-909379de1cc9');

if ($tenant) {
    tenancy()->initialize($tenant);

    \Artisan::call('migrate', [
        '--path' => 'database/migrations/tenant/2025_02_10_133148_create_menus_table.php',
        '--force' => true,
    ]);

    echo \Artisan::output();
} else {
    echo "Tenant not found!";
}

<!-- call a seeder like this  -->
if ($tenant) {
    tenancy()->initialize($tenant);

    \Artisan::call('db:seed', [
        '--class' => 'MenuSeeder',
    ]);

    echo \Artisan::output();
} else {
    echo "Tenant not found!";
}