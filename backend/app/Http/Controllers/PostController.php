<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Year;

class PostController extends Controller
{
    public function index()
    {
        return Post::select(
                'posts.id', 
                'posts.title', 
                'posts.body', 
                'posts.created_at', 
                'posts.updated_at', 
                'years.year',
                'users.name as user_name'
            )
            ->join('years', 'posts.year_id', '=', 'years.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->get();
    }

    public function show(Request $request)
    {
        $q = $request->input('phrase');
        $posts = Post::query()
            ->join('years', 'posts.year_id', '=', 'years.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->where(function ($query) use ($q) {
                $query->where('posts.title', 'LIKE', "%{$q}%")
                      ->orWhere('posts.body', 'LIKE', "%{$q}%");
            })
            ->select('posts.id', 'posts.title', 'posts.body', 'posts.created_at', 'posts.updated_at', 'years.year', 'users.name as user_name')
            ->get();
    
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'Post sa nenašiel'], 404);
        }
    
        return response()->json($posts, 200);
    }

    public function create(Request $request)
    {
        $user = $request->user();
    
        if (!$user) {
            return response()->json(['message' => 'Neprihlásený používateľ'], 401);
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'year_id' => 'required|exists:years,id',
        ]);
    
        if ($user->role_id == 2) {
            $yearId = $request->input('year_id');
            $year = Year::find($yearId)->year;
            $userYears = $user->years->pluck('year')->toArray();
            
            if (!in_array($year, $userYears)) {
                return response()->json(['message' => 'Unauthorized to create post for this year'], 403);
            }
        }
    
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('image'),
            'user_id' => $user->id,
            'year_id' => $request->input('year_id'),
        ]);
    
        return response()->json(['message' => 'Post sa vytvoril'], 201);
    }

    public function destroy(Request $request, string $id)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Neprihlásený používateľ'], 401);
        }
        
        $post = Post::find($id);
    
        if (!$post) {
            return response()->json([
                'message' => 'Post sa nenašiel'
            ], 404);
        }
        
        if ($user->role_id == 2) {
            $userYears = $user->years->pluck('year')->toArray();
            $postYear = Year::find($post->year_id)->year;
            
            if (!in_array($postYear, $userYears)) {
                return response()->json(['message' => 'Unauthorized to delete this post'], 403);
            }
        }
    
        $post->delete();
    
        return response()->json(['message' => 'Post sa odstranil'], 200);
    }

    public function getPostById($id)
    {
        try {
            $post = Post::join('years', 'posts.year_id', '=', 'years.id')
                ->join('users', 'posts.user_id', '=', 'users.id')
                ->where('posts.id', $id)
                ->select('posts.*', 'years.year', 'users.name as user_name')
                ->first();
        
            if (!$post) {
                return response()->json(['message' => 'Post not found'], 404);
            }
            
            $responseData = $post->toArray();
            
            return response()->json($responseData, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function newestPost()
    {
        $post = Post::join('years', 'posts.year_id', '=', 'years.id')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->orderBy('posts.created_at', 'desc')
            ->select('posts.*', 'years.year', 'users.name as user_name')
            ->first();
        return response()->json($post, 200);
    }

    public function update(Request $request, $id)
{
    try {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Neprihlásený používateľ'], 401);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        if ($user->role_id == 2) {
            $userYears = $user->years->pluck('year')->toArray();
            $postYear = Year::find($post->year_id)->year;
            
            if (!in_array($postYear, $userYears)) {
                return response()->json(['message' => 'Unauthorized to edit this post'], 403);
            }
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'year_id' => 'required|exists:years,id',
        ]);

        if ($user->role_id == 2) {
            $newYearId = $request->input('year_id');
            $newYear = Year::find($newYearId)->year;
            
            if (!in_array($newYear, $userYears)) {
                return response()->json(['message' => 'Unauthorized to assign post to this year'], 403);
            }
        }

        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->year_id = $request->input('year_id');
        
        $post->save();

        return response()->json([
            'message' => 'Post bol úspešne aktualizovaný',
            'post' => $post
        ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error updating post',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
