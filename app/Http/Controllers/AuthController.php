<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register (Request $request) {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('blockchain')->plainTextToken;

        return response()->json([
            'message' => 'Registration successfuly',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
        ]);
    }

    public function login (Request $request) {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect'
            ]);
        }

        $token = $user->createToken('blockchain')->plainTextToken;

        return response()->json([
            'message' => 'Login successfuly',
            'token' => $token
        ]);
    }

    public function currentUser (Request $request) {
        return response()->json([
            'message' => 'User fetched successfully',
            'data' => new UserResource($request->user())
        ]);
    }

    public function logout (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successfuly'
        ]);
    }
}
