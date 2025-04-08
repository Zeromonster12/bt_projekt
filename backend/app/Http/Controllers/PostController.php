<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
    public function index()
    {
        return Post::select('image', 'title', 'body')->get();
    }

    public function show(Request $request)
    {
        $q = $request->input('phrase');
        $posts = Post::query()
            ->where(function ($query) use ($q) {
                $query->where('title', 'LIKE', "%{$q}%")
                      ->orWhere('body', 'LIKE', "%{$q}%");
            })
            ->get();

        if ($posts->isEmpty()) {
            return response()->json(['message' => 'Post sa nenašiel'], 404);
        }

        return response()->json($posts, 200);
    }

    public function create(Request $request)
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'image' => $request->input('image'),
            'user_id' => 1
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
}
