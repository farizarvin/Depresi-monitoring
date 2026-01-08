<?php

namespace App\Http\Controllers\Auth\Sanctum;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\Concerns\Has;

class LoginController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', only : ['postLogout'])
        ];
    }
    public function postLogin(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required'
        ]);
        if($validator->fails())
        {
            $response=
            [
                'message'=>'Input salah.',
                'errors'=>$validator->errors()
            ];
            return response()->json($response, 422);
        }
        
        $credentials=$validator->validated();
        $user=User::where('username', $credentials['username'])->first();
        if($user && Hash::check($credentials['password'], $user->password))
        {
            $token=$user->createToken('api-token')->plainTextToken;
            $response=
            [
                'message'=>'Login berhasil',
                'token'=>$token
            ];
            return response()->json($response, 200);
        }
        $response=['credential'=>'username/password salah'];
        return response()->json($response, 401);
    }
    public function postLogout(Request $request)
    {
        Auth::guard('api')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
