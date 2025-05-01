<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

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

    public function updateUser(Request $request) {
        try {
            $user = User::findOrFail($request->id);
            $user->update($request->only(['name', 'email', 'role_id', 'years']));
            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update user', 'details' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->save();

        return response()->json($user, 200);
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
        $users = User::all();

        return response()->json($users);
    }
}
