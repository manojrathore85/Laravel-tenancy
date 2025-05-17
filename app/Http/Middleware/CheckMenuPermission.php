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
            abort(401, 'User Not authticated');
        }
        //Treat user id 1 as super admin
        if ($user && $user->is_super_admin === 1) {
            return $next($request);
        }
        // check project context set or not and get role for the project 
        if(!$request->hasHeader('x-login-project-id')) {
            abort(461, 'Project Context Not Found');
        }
        // else {
        //     $project_id = $request->header('X-Login-Project-ID');
        //     $hasRole = $user->getRoleForProject($project_id);
        //     if (!$hasRole){
        //         abort(403, 'Unauthorized For Project: '.$project_id);
        //     }
        // }

        // Fetch menu by slug
        $menuRecord = Menu::where('route', $menu)->first();
        if (!$menuRecord) {
            abort(462, 'Menu: '.$menu. ' not found');
        }
        

        // Check permission in role_menu_permissions table
        $hasPermission = RoleMenuPermission::where('role_id', $request->user()->role->id)
            ->where('menu_id', $menuRecord->id)
            ->where($action, true)
            ->exists();
    
        if (!$hasPermission) {          
            abort(403, 'Unauthorized For Menu: '.$menuRecord->name.' action: '.$action);
        }
      
        return $next($request);
    }
}
