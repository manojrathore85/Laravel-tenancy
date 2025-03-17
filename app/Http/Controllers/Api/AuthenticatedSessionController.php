<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();
        try {
            //$user = $request->user();
            $user = User::find($request->user()->id);
            $token = $request->user()->getOrCreateToken($user->email, ['*'], now()->addDays(30))->plainTextToken;
            //$token = $request->user()->createToken($user->email)->plainTextToken;
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
        
        
        //$request->session()->regenerate();

        // return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
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
}
