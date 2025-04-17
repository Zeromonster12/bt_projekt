<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
    public function index()
    {
        return Post::select('id', 'image', 'title', 'body', "year")->get();
    }

    public function show(Request $request)
    {
        $q = $request->input('phrase');
        $posts = Post::query()
            ->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', "%{$q}%")
                      ->orWhere('body', 'LIKE', "%{$q}%");
            })
            ->get(); //dorobit ked bude WYSIWYG

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'Post sa nenašiel'], 404);
        }

        return response()->json($posts, 200);
    }

    public function create(Request $request)
    {


        $user = Auth::user();

        if(!$user) {
            return response()->json(['message' => 'Neprihlásený používatel - POSTCONTROLLER'], 401); 
        }

        $role = $user->role_name;

        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('image'),
            'user_id' => 2,
            'year_id' => 2,
        ]);


        return response()->json(['message' => 'Post sa vytvoril'], 201);
    }

    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Post sa nenašiel'
            ], 404);
        }

        $post->delete();

        return response()->json(['message' => 'Post sa odstranil'], 200);
    }

    public function getPostById($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json($post, 200);
    }
}
