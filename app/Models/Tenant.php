<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\DatabaseConfig;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;

    protected $guarded = ['id'];    

    public static function getCustomColumns():array
    {
        return [
        'id',
        'name',
        'email', 
        'database_name',             
        'password'
        ];
    }

    public function setPasswordAtribute($value){
        return $this->attributes['password'] = bcrypt($value);
    } 

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Tenant $tenant) {
            // Generate database name using the custom generator
            $databaseName = (DatabaseConfig::$databaseNameGenerator)($tenant);

            // Store database name in the tenant model
            $tenant->setAttribute('database_name', $databaseName);
        });
    }   
}