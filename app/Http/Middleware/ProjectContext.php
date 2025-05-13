<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //Treat user id 1 as super admin
        if ($request->user()->is_super_admin === 1) {
            return $next($request);
        }
        // check project context set or not and get role for the project 
        // dd($request->user()->assigned_projects->toArray());
        if(!$request->user()->assigned_projects->toArray()){
            abort(461, 'You dont have any assigned projects');
        }
        if(!$request->hasHeader('X-Login-Project-ID') || empty($request->header('X-Login-Project-ID'))) {
            abort(462, 'Project Context Not Found from project context');
        }

        else{
            $project_id = $request->header('X-Login-Project-ID');
            $userHashProject = $request->user()->getRoleForProject($project_id);           
            if (!$userHashProject->role){
                abort(403, 'Unauthorized For Project: '.$project_id);
            }
            
            $request->user()->role = $userHashProject->role;
        }
        
        
        return $next($request);
    }
}
