<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Post::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'required|image|mimes:jpeg,png,jpg,gif',
            'post_type' => 'required|in:technical,medical,informational,descriptive',
            'status' => 'in:inactive,active'
        ]);
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                $images[] = $path;
            }
        }
        dd($images);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'images' => json_encode($images),
            'post_type' => $request->post_type,
            'status' => $request->status ?? 'inactive',
        ]);

        $post->images = $images;

        // $post = Post::create($request->all());

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'post not found'],404);
        }

        return response()->json($post,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'post not found'], 400);
        }

        $post->update($request->all());
        return response()->json($post,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if(!$post){
            return response()->json(['message' => 'post is not found'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'post deleted successfully'], 200);
    }
}
