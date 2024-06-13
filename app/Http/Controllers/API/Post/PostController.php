<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\PostAddRequest;
use App\Http\Requests\PostEditRequest;

class PostController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $posts=Post::where('status',"1")->get();

        if($posts->isEmpty()){
            return $this->successResponse([],'No posts available',404);
        }

        return $this->successResponse($posts,'Posts fetched successfuly',200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created post in storage.
     *
     * @param \App\Http\Requests\PostAddRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PostAddRequest $request)
    {

        $validated = validator($request->all());

        if($validated->fails()){
            return $this->validationError($validated,"Validations failed");
        }
    
        // Handle file uploads
        $uploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $name, 'public');
                $uploadedImages[] = $name;
            }
        }
    
        try {
            // Create the post
            // $user=auth()->user();
            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'images' => $uploadedImages,
                'user_id' => 1,
                'post_type' => $request->post_type
            ]);

            return $this->successResponse($post,'Post created successfuly',201);

        } catch(\Exception $e) {

            return $this->errorResponse("Something went wrong, post not created");

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
