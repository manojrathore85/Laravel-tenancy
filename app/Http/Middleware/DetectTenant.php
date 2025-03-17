<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Tenancy;

class DetectTenant
{
    public function handle($request, Closure $next)
    {
        // print_r($request->hasHeader('X-Tenant-Domain'));
        
        
        // if ($request->hasHeader('X-Tenant-Domain')) {
        //     $domain = $request->header('X-Tenant-Domain');
        //     Log::info("domain name is ".$domain);
        //     tenancy()->initialize($domain);
        // }
        return $next($request);
    }
}
