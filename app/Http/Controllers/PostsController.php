<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // GET /posts?search=lumen&status=published&category_id=1&per_page=5
    public function index(Request $request)
    {
        $posts = Post::orderBy("id", "DESC")->paginate(2)->toArray();
        $response = [
            "total_count" => $posts["total"],
            "limit"       => $posts["per_page"],
            "pagination"  => [
                "next_page" => $posts["next_page_url"],
                "current_page" => $posts["current_page"]
            ],
            "data" => $posts["data"],
        ];

        return response()->json($response, 200);
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

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];
        
        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = Post::create($input);
        return response()->json($post, 200);
    }

     // PUT /posts/{id} -> validasi Accept + Content-Type
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $post = Post::find($id);

        if (!$post) {
            abort(404);
        }

        $validationRules = [
            'title' => 'required|min:5',
            'content' => 'required|min:10',
            'status' => 'required|in:draft,published',
            'user_id' => 'required|exists:users,id'
        ];
        
        $validator = \Validator::make($input, $validationRules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post->fill($input);
        $post->save();

        return response()->json($post, 200);
        
    }

    // DELETE /posts/{id} -> validasi Accept
    public function destroy(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $post = Post::find($id);

            if (!$post) {
                abort(404);
            }

            $post->delete();

            if ($acceptHeader === 'application/json') {
                return response()->json(['message' => 'deleted successfully', 'post_id' => $id], 200);
            } else {
                $xml = new \SimpleXMLElement('<result/>');
                $xml->addChild('message', 'deleted successfully');
                $xml->addChild('post_id', $id);

                return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
            }
        } else {
            return response('Not Acceptable!', 406);
        }
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