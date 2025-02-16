<?php

namespace App\Http\Controllers\Api;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return   Tenant::All();
        //do this all tenants with comasepteted domains
        $tenants = Tenant::with('domains')->get();
        $tenantsWithCommaSeparatedDomains = $tenants->map(function ($tenant) {
            return [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'created_at' => $tenant->created_at->format('d M Y h:i A'),
                'domains' => $tenant->domains->pluck('domain')->implode(', '),
            ];
        });
        return $tenantsWithCommaSeparatedDomains;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      //  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'name' =>  'required|unique:tenants,name',
            'email' => 'required|email|unique:tenants,email',         
            'password' => 'required|confirmed',
            'domain_name' => 'required|unique:domains,domain',
            'phone' => 'required|unique:tenants,phone',
        ]);
       // dd($validatedData);
        try {
            $tenant = Tenant::create($validatedData);
            $tenant->domains()->create([
                'domain' => $validatedData['domain_name'].'.'.config('app.domain'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Tenant Created successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }
 
        
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function show($id)
    {
        $tenant = Tenant::with('domains')->find($id);
        $domain = $tenant->domains->first()->domain;
        $replaceString = ".".config('app.domain');
        $tenant->domain = str_replace($replaceString, '', $domain);
        return $tenant;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        try {
            $tenant = Tenant::find($request->id);
            $tenant->update($request->except('id', 'email'));
            $domain = $tenant->domains()->first();
            $domain->domain = $request->domain_name.'.'.config('app.domain');
            $domain->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Tenant Updated successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
       
        try {                    
            $tenant->delete();           
            return response()->json([
                'status' => 'success',
                'message' => 'Your Account is deleted successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }  
        
    }
}
