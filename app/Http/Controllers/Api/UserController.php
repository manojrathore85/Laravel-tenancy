<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request) {
        return User::All();
    }
    public function store(){
        //
    }
    public function show(Request $request, $id) {
        return User::find($id);
    }
    public function update(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'gender'=> 'required',
            'phone' => 'required|unique:users,phone,'.$request->id,
        ]);
        try {    
            $user = User::find($request->id);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'phone' => $request->phone
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Updated successfully',             
                'data' => $user,
            ], 200);
        
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ], 500);
        }  

    }
    public function destroy($ids) {
       
        if(empty($ids)) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Ids not found',                             
            ], 400);          
        }
        try {   
            $idsArray = explode(',', $ids);           

            User::destroy($idsArray);
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

}   
