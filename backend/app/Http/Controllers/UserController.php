<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function createUser(Request $request) {
        $user = User::create($request->only(['name', 'surname', 'email', 'password']));
        $role = Role::where('name', $request->input('role'))->firstOrFail();
        $user->roles()->attach($role->id);
        return response()->json(['message' => 'User created successfully']);
    }

    public function deleteUser(User $user) {
        $user->roles()->detach();
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function updateUser(Request $request, User $user) {
        $user->update($request->only(['name', 'surname', 'email', 'password']));
        if ($request->has('role')) {
            $role = Role::where('name', $request->input('role'))->firstOrFail();
            $user->roles()->sync([$role->id]);
        }
        return response()->json(['message' => 'User updated successfully']);
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
}
