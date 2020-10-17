<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    public function login(User $route,Request $request) 
    {   

        $creds = $request->only(['email', 'password']);
        
        if(!Auth::attempt($creds)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials']);
        }

        $tokenResult = Auth::user()->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'success'=> true,
            'token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    public function register(Request $request)
    {

        try {

            
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->save();

            return response()->json([
                'success'=> true,
                'message' => 'Successfully created user!',
                'token' => 'no'
            ]);
            
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json([
                'success'=> false
            ]);
        }

    }

    public function logout(Request $request) 
    {
    	$request->user()->token()->revoke();
        return response()->json([
            'success'=> true,
            'message' => 'Successfully logged out'
        ]);
    }

}
