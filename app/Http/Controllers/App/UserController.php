<?php

namespace App\Http\Controllers\App;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('domains')->get();
       
        return view('user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'name' =>  'required|unique:users,name',
            'email' => 'required|email|unique:users,email',         
            'password' => 'required|confirmed',
            'domain_name' => 'required|unique:domains,domain',
        ]);
       // dd($validatedData);
        $user = User::create($validatedData);
        $user->domains()->create([
            'domain' => $validatedData['domain_name'].'.'.config('app.domain'),

        ]);
        return redirect()->route('users.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Tenant deleted successfully.');
    }
}
