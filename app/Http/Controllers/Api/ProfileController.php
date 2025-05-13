<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit($id)
    {
        return User::find($id);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = User::find($request->id);
            $user->update($request->except('id', 'email'));
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

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
      try {
            $user = User::find($request->id);            
            $user->delete();
            //$request->session()->invalidate();
            //$request->session()->regenerateToken();
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
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->password);
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
          
            $user = User::find($id);            
            if ($request->hasFile('profile_image')) {               
                $file = $request->file('profile_image');
                $path = $file->store('User', 'public');             
                $data['profile_image'] = $path;               
            }
            $user->update($data);

            $updatedUser = User::find($id);
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
}
