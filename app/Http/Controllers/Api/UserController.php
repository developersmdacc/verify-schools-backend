<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // Require authentication for all routes
        $this->middleware('auth:sanctum');
    }

    /**
     * List all users (with optional search and pagination)
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Optional search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }

        $users = $query->with('roles')->paginate(15);

        return response()->json($users);
    }

    /**
     * Show single user by ID
     */
    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update user info
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'firstname' => 'sometimes|string|max:255,' . $user->id,
            'lastname' => 'sometimes|string|max:255,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'profile_photo' => 'nullable|url',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'role' => 'nullable|string|exists:roles,name',
            'country_id' => 'nullable|exists:countries,id',
            'province_id' => 'nullable|exists:provinces,id',
            'city_id' => 'nullable|exists:cities,id',
        ]);


        // Update user fields
        $fields = $request->only([
            'name','firstname','lastname','username','email','phone','profile_photo','status',
            'bio','address','dob','country_id','province_id','city_id'
        ]);

        if ($request->password) {
            $fields['password'] = Hash::make($request->password);
        }

        $user->update($fields);

        // Update role if provided
        if ($request->role) {
            $user->syncRoles([$request->role]);
        }

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user->load(['roles', 'country', 'province', 'city'])
        ]);

    }

    /**
     * Delete user (admin only)
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
