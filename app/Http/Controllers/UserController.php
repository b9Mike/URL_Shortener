<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(Request $request){
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'profile_image' => 'nullable|string',
            ]);
            // Crear el usuario
            User::create([
                'name' => $validated['name'],
                'role' => 'user',
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'profile_image' => $validated['profile_image'] ?? null,
            ]);

            return response()->json([
                'success' => "user created successfully"
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
