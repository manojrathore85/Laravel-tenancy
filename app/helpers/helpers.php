<?php 

if (!function_exists('tenant_url')) {
    function tenant_url($type = 'backend'): string
    {
        $tenant = tenant();
        $protocol = config('app.tenant_protocol');
        if($type === 'frontend'){
            $base = $tenant->domains()->first()->frontend_url;
        }else{
            $base = $tenant->domains()->first()->domain;
        }
        return "{$protocol}://{$base}";
    }
}
