<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('pages', 10);
            $users = User::paginate($perPage);
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal error List Users'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $existingUser = User::where('email', $request->input('email'))->first();
            if ($existingUser) {
                return response()->json(['error' => 'User with this email already exists'], 422);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'active' => 'boolean',
            ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'active' => $request->input('active', true),
            ]);

            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal error Create User'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Validar si otro usuario ya tiene el mismo correo electrónico
            $existingUser = User::where('email', $request->input('email'))->where('id', '!=', $user->id)->first();
            if ($existingUser) {
                return response()->json(['error' => 'Another user already has this email'], 422);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'required|string|min:8',
                'active' => 'boolean',
            ]);

            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'active' => $request->input('active', true),
            ]);

            return response()->json(['message' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    /**
     * Activate or deactivate the specified resource in storage.
     */
    public function status(User $user, $status)
    {
        try {
            $user->update(['active' => $status]);
            $message = $status ? 'User activated successfully' : 'User deactivated successfully';
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal error Update User'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal error Deleted User'], 500);
        }
    }
}
