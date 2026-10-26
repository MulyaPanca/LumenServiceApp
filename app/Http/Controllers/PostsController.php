<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    // GET /posts?search=lumen&status=published&category_id=1&per_page=5
    public function index(Request $request)
    {
        $acceptHeader = $request->header('Accept');

        if($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {
            $posts = Post::orderBy("id", "DESC")->paginate(10);

            if ($acceptHeader === 'application/json') {
                return response()->json($posts->items('data'), 200);
            }else {
                $xml = new \SimpleXMLElement('<posts/>');
                foreach ($posts->items('data') as $item) {
                    $xmlItem = $xml->addChild('post');

                    $xmlItem->addChild('id', $item->id);
                    $xmlItem->addChild('title', $item->title);
                    $xmlItem->addChild('status', $item->status);
                    $xmlItem->addChild('content', $item->content);
                    $xmlItem->addChild('user_id', $item->user_id);
                    $xmlItem->addChild('category_id', $item->category_id);
                    $xmlItem->addChild('created_at', $item->created_at);
                    $xmlItem->addChild('updated_at', $item->updated_at);
                }
                return $xml->asXML();
            }
        } else {
            return response('Not Acceptable', 406);
        }
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
        $acceptHeader = $request->header('Accept');
        
        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();
                $post = Post::create($input);

                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 201);
                } else {
                    $xml = new \SimpleXMLElement('<post/>');

                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', htmlspecialchars($post->title));
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', htmlspecialchars($post->content));
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('category_id', $post->category_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 201)->header('Content-Type', 'application/xml');
                }
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable', 406);
        }

    }

     // PUT /posts/{id} -> validasi Accept + Content-Type
    public function update(Request $request, $id)
    {
        $acceptHeader = $request->header('Accept');

        if ($acceptHeader === 'application/json' || $acceptHeader === 'application/xml') {

            $contentTypeHeader = $request->header('Content-Type');

            if ($contentTypeHeader === 'application/json') {
                $input = $request->all();

                $post = Post::find($id);

                if (!$post) {
                    abort(404);
                }

                $post->fill($input);
                $post->save();

                if ($acceptHeader === 'application/json') {
                    return response()->json($post, 200);
                } else {
                    $xml = new \SimpleXMLElement('<post/>');

                    $xml->addChild('id', $post->id);
                    $xml->addChild('title', htmlspecialchars($post->title));
                    $xml->addChild('status', $post->status);
                    $xml->addChild('content', htmlspecialchars($post->content));
                    $xml->addChild('user_id', $post->user_id);
                    $xml->addChild('category_id', $post->category_id);
                    $xml->addChild('created_at', $post->created_at);
                    $xml->addChild('updated_at', $post->updated_at);

                    return response($xml->asXML(), 200)->header('Content-Type', 'application/xml');
                }
            } else {
                return response('Unsupported Media Type', 415);
            }
        } else {
            return response('Not Acceptable!', 406);
        }
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