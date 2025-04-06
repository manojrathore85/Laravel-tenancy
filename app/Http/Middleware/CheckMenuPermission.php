<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\RoleMenuPermission;
use App\Models\Menu;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $menu, $action): Response
    {
       
        
        $user = Auth::user();
        if (!$user) {
            abort(403, 'User Not authticated');
        }

        // Fetch menu by slug
        $menuRecord = Menu::where('route', $menu)->first();
        if (!$menuRecord) {
            abort(404, 'Menu: '.$menu. ' not found');
        }
        if(!$user->roles->first()){
            abort(404, 'User: '.$user->name. 'Role not found');
        };
        // Check permission in role_menu_permissions table
        $hasPermission = RoleMenuPermission::where('role_id', $user->roles->first()->id)
            ->where('menu_id', $menuRecord->id)
            ->where($action, true)
            ->exists();
    
        if (!$hasPermission) {          
            abort(403, 'Unauthorized For Menu: '.$menuRecord->name.' action: '.$action);
        }
      
        return $next($request);
    }
}
