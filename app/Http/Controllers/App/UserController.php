<?php

namespace App\Http\Controllers\App;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
       
        return view('App.user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ModelsRole::pluck('name', 'name')->all();
        return view('App.user.create', [ 'user' => null, 'roles' => $roles]);
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
            'role' => 'required',
            'phone' => 'required|unique:users,phone',
        ]);
       // dd($validatedData);
        $user = User::create($request->all());
        $user->assignRole($request->role);
        return redirect()->route('user.index')->with('success', 'User created successfully.');
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
        $roles = ModelsRole::pluck('name', 'name')->all();
        return view('App.user.create', ['user' => $user, 'roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' =>  'required|unique:users,name,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,           
            'role' => 'required',
            'phone' => 'required|unique:users,phone,'.$user->id,
        ]);
        $user->update($request->all());
        $user->syncRoles($request->role);
        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
