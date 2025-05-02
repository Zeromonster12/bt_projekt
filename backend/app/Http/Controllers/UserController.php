<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller {
    public function createUser(Request $request) {
        $request->request->add(['password' => Str::random(8)]);
        try {
            $user = User::create($request->only(['name', 'role_id', 'email', 'password']));
            return response()->json(['message' => 'User created successfully with password: ' . $request->password]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user', 'details' => $e->getMessage()], 500);
        }
    }

    public function deleteUser(User $user) {
        $user->roles()->detach();
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUser(Request $request) {
        try {
            $user = User::findOrFail($request->id);
            $user->update($request->only(['name', 'email', 'role_id', 'years']));
            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user', 'details' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message' => 'Nesprávne prihlasovacie údaje.'], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);

    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Úspešné odhlásenie']);
    }

    public function fetchUsers() {
        $users = User::with('years')->get();
    
        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'role_id' => $user->role_id,
                'name' => $user->name,
                'email' => $user->email,
                'years' => $user->years->pluck('year')->toArray(), 
            ];
        });
    
        return response()->json($users);
    }
}
