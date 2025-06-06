<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;

class UserController
{
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|integer',
        ]);
    
        try {
            $password = Str::random(12);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($password)
            ]);
    
            Mail::to($user->email)->send(new UserCreatedMail($user, $password));
    
            return response()->json(['message' => 'User created successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'User creation failed.',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function updateUser(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->update($request->only(['name', 'email', 'role_id']));

            if (isset($request->years)) {
                $user->years()->sync($request->years);
            }

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

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->remember)) {
            return response()->json([
                'message' => 'Nesprávne prihlasovacie údaje.'
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'pfp' => $user->pfp,
            ],
            'message' => 'Prihlásenie bolo úspešné.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json(['message' => 'Odhlásenie prebehlo úspešne.']);
    }

    public function fetchUsers()
    {
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

    public function getUser(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Not logged in'], 401);
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
            'pfp' => $user->pfp,
            'years' => $user->years->pluck('year')->toArray(),
        ]);
    }

    public function getProfile(Request $request)
    {
        return response()->json([
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'role_id' => $request->user()->role_id,
        ]);
    }
}
