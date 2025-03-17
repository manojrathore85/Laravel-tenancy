<?php

namespace App\Http\Controllers\Api\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\TenantUserRegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
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
            $users = TenantUser::all();
            return response()->json($users, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage(),                
            ], 500);
        }
    }
    public function store(TenantUserRegisterRequest $request){
    
        try {  
           // DB::beginTransaction(); //
            $user = TenantUser::create([
                'name' => $request->name,
                'email' => $request->email,               
                'password' =>$request->password,
                'gender' => $request->gender,
                'phone' => $request->phone,
            ]);
            echo "wroking or not";
            print_r($user);
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
            $user = TenantUser::find($request->user()->id);
            $token = $request->user()->createToken($user->email)->plainTextToken;
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
  }

