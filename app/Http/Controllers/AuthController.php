<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' =>'required|string|email|min:8|max:50',
            'password' =>'required|string|min:6'
        ]);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
            return response()->json(['token' => $token], 200);
        }catch(JWTException $e){
            return response()->json(['error' => 'Could not create token: ', $e], 500);
        }
    }

    public function getUser(){
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
         return response()->json(['message' => 'Logged out successfully'], 200);
    }

}
