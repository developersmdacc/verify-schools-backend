<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // In register()
        $role = $request->role ?? 'visitor'; // default

        if(!in_array($role, ['parent','learner','vendor','driver','visitor'])) {
            $role = 'visitor';
        }

        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|url',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
        ]);

        $user = User::create([
            'name' => $request->firstname . " " . $request->lastname,
            'username' => $request->email,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
            'phone' => $request->phone,
            'profile_photo' => $request->profile_photo,
            'status' => $request->status ?? 'active',
            'bio' => $request->bio,
            'address' => $request->address,
            'dob' => $request->dob,
        ]);

        $user->assignRole($role);

        // Optionally fire Registered event for email verification
        // event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        // 1️⃣ Validate input
        $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|string'
        ]);

        // 2️⃣ Find the user
        $user = User::where('email', $request->email)->first();

        // 3️⃣ Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.'
            ], 401); // 401 Unauthorized
        }

        // 4️⃣ Create Sanctum token
        $token = $user->createToken('api-token')->plainTextToken;

        // 5️⃣ Return user, token, roles, permissions
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user->load(['country', 'province', 'city']),
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }

}
