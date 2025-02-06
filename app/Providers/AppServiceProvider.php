<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\DatabaseConfig;
use Stancl\Tenancy\Contracts\TenantWithDatabase as Tenant;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //package method is overriding here to manange data base name create as per cour loogic
        DatabaseConfig::generateDatabaseNamesUsing(function (Tenant $tenant) {
            return  config('tenancy.database.prefix'). $tenant->getTenantKey() ."-".$tenant->name. config('tenancy.database.suffix') ;
        });

    }
}
