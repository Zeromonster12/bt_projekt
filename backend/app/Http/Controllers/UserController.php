<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Str;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|integer',
        ]);

        $password = Str::random(8);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($password)
        ]);

        return response()->json(['message' => 'User created successfully with password: ' . $password]);
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
        $token = $user->createToken('auth-token')->plainTextToken;
        
        // Vytvorenie response
        $response = response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
            ],
            'message' => 'Prihlásenie bolo úspešné.'
        ]);
        
        // Pridanie HTTP-only cookie s tokenom
        $response->cookie(
            'auth_token',
            $token,
            120, // 2 hodiny expirácia
            '/',
            null,
            config('app.env') === 'production', // secure len v produkcii
            true, // HTTP only
            false, // same site
            'lax' // same site policy
        );
        
        return $response;
    }

    public function logout(Request $request)
    {
        // Zrušenie všetkých tokenov používateľa
        if ($request->user()) {
            $request->user()->tokens()->delete();
        }
        
        // Odhlásenie z web guard
        Auth::guard('web')->logout();

        // Zneplatnenie session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Vytvorenie response a zrušenie cookie
        $response = response()->json(['message' => 'Odhlásenie prebehlo úspešne.']);
        $response->cookie('auth_token', '', -1); // Zrušenie cookie
        
        return $response;
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
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => $user->role_id,
        ]);
    }
}
