<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $guarded = ['id'];

    public static function getCustomColumns():array
    {
        return [
        'id',
        'name',
        'email',
        'password'
        ];
    }

    public function setPasswordAtribute($value){
        return $this->attributes['password'] = bcrypt($value);
    }
}