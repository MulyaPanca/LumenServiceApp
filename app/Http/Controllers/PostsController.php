<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // GET /posts?search=lumen&status=published&category_id=1&per_page=5
    public function index()
    {
        $posts = Post::orderBy("id", "DESC")->paginate(10);

        $output = [
            "message" => "posts",
            "results" => $posts
        ];

        return response()->json($posts, 200);
    }

    // GET /posts/{id}
    public function show($id)
    {
        $post = Post::with('category')->findOrFail($id);

        return response()->json($post, 200);
    }

    // POST /posts
    public function store(Request $request)
    {
        $input = $request->all();
        $post = Post::create($input);

        return response()->json($post, 200);
    }

    // PUT /posts/{id}
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $this->validate($request, [
            'title'       => 'max:100',
            'status'      => 'in:draft,published',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $post->update($request->only('title', 'content', 'status', 'category_id'));

        return response()->json($post->load('category'), 200);
    }

    // DELETE /posts/{id}
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();

        return response()->json(['message' => 'Post deleted'], 200);
    }

    // GET /posts-stats : jumlah post per status
    public function stats()
    {
        $stats = Post::selectRaw('status, COUNT(*) as total')
                     ->groupBy('status')
                     ->get();

        return response()->json($stats, 200);
    }
}