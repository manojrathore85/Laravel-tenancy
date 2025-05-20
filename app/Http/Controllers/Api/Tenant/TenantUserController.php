<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TenantUserRegisterRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Models\Tenant;
use App\Models\Tenant\Project;
use App\Models\Tenant\User as TenantUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantUserController extends Controller
{
    public function index(){
        try {
            $users = TenantUser::with('roles')->get();
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),                
            ], 500);
        }
    }
    public function register(TenantUserRegisterRequest $request){
    
        try {  
            $user = TenantUser::create([
                'name' => $request->name,
                'email' => $request->email,               
                'password' =>$request->password,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'status' => 1,
            ]);
            event(new Registered($user));    
            return response()->json([
                'status' => 'success',
                'message' => 'User Registerd successfully activation will be done by admin',               
            ], 200);
          
        } catch (\Throwable $th) {          
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }  
    }
    public function store(TenantUserRegisterRequest $request){
    
        //return response()->json($request->timezone);    
        try {  
           // DB::beginTransaction(); //
            $user = TenantUser::create([
                'name' => $request->name,
                'email' => $request->email,               
                'password' =>$request->password,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'is_super_admin' => $request->is_super_admin,
                'timezone' => $request->timezone,
            ]);
            $user->assignRole($request->role);
            //$user->assignRole('user');
            //$user->assignRole('user');

            
            event(new Registered($user));

            //Auth::login($user);
            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'token' => $user->createToken($user->email)->plainTextToken,
                'data' => $user,
            ], 200);
            //DB::commit();
        } catch (\Throwable $th) {
           // DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }  
    }
    public function login(LoginRequest $request):JsonResponse
    {
        $request->authenticate();
        try {
            //$user = $request->user();
            $user = TenantUser::find(auth()->guard('tenant')->user()->id);
        
            //$user->assignedRoles = $user->getRoleNames(); 
            if($user->is_super_admin){
                $assignedProjects = Project::all();
            }else{
                $assignedProjects = $user->assigned_projects; 
            }
            if(!empty($assignedProjects->toArray())) {
                $user->assigned_projects = $assignedProjects;  
                $user->loginProjectId = $assignedProjects[0]->id;   
            }
            $token = $user->createToken($user->email)->plainTextToken;
            return response()->json([
                'status' => 'success', 
                'message' => 'User logged in successfully', 
                'user' => $user,
                'token' => $token], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),    
            ],401);
        }
    }
    public function logout(Request $request)
    {
        $token = $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->first();
        $token->delete();
        // $token->expires_at = now()->subMinute();
        // $token->save();
    
        return response()->json([
            'message' => 'Logged out successfully',
            'status' => 'success'
        ]);        
    }
    public function show($id){
        try { 
            $user = TenantUser::with('roles')->find($id);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),
            ], 500);
        }  
    }
    public function update(TenantUserRegisterRequest $request, $id){      
        try {
            $user = TenantUser::find($id);
            $user->update($request->all());
            $user->syncRoles($request->role);
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }
    }
    public function destroy($ids){
        if(empty($ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Ids not found',                             
            ], 400);          
        }
        try {   
            $idsArray = explode(',', $ids);           

            TenantUser::destroy($idsArray);
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',            
            ], 200);
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function profileUpdate(TenantUserRegisterRequest $request, $id){      
        try {
            $user = TenantUser::find($id);
            $user->update($request->all());           
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }
    }
    public function changePassword(Request $request){

        $request->validate([
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', 'string', 'min:8', 'max:16','confirmed', 'different:current_password'],
        ]);
        try {
            // Update password
            $user = TenantUser::find(Auth::user()->id);
            $user->password = $request->password;
            $user->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Password Updated successfully',              
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),              
            ], 500);
        }
    }
    public function uppdateProfileImage(string $id, Request $request){
        $request->validate([
           'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);
        try {
            $data = $request->all();
          
            $user = TenantUser::find($id);            
            if ($request->hasFile('profile_image')) {               
                $file = $request->file('profile_image');
                $path = $file->store('User', 'public');             
                $data['profile_image'] = $path;               
            }
            $user->update($data);

            $updatedUser = TenantUser::find($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Image updated successfully',
                'data'=>$updatedUser
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }
     
    }
    public function getUsersByProject(Project $project){
        try {
            $users = $project->users()->with('roles')->get();
            //$users = TenantUser::with('roles')->get();
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),                
            ], 500);
        }
    }
  }

