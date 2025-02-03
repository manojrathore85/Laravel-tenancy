<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::with('domains')->get();
       
        return view('tenant.index', ['tenants' => $tenants]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.create');
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
        ]);
       // dd($validatedData);
        $tenant = Tenant::create($validatedData);
        $tenant->domains()->create([
            'domain' => $validatedData['domain_name'].'.'.config('app.domain'),

        ]);
        return redirect()->route('tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
       
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
