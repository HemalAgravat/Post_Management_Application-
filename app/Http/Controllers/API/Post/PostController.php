<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\PostAddRequest;
use App\Http\Requests\PostEditRequest;


/**
 * Class PostController
 *
 * @package App\Http\Controllers\API\Post
 *
 * Controller for handling CRUD operations related to posts.
 */
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
        try {
            $posts=Post::where('status',"1")->get();

            if($posts->isEmpty()){
                return $this->successResponse([],'messages.post_messages.not_found',404);
            }

            return $this->successResponse($posts,'messages.post_messages.fetched',200);
        } catch(\Exception $e) {

            return $this->errorResponse("error".$e->getMessage());

        }
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
            return $this->validationError($validated,'messages.validation.failed');
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
            $user=auth()->user();
            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'images' => $uploadedImages,
                'user_id' => $user->id,
                'post_type' => $request->post_type
            ]);

            return $this->successResponse($post,'messages.post_messages.success',201);

        } catch(\Exception $e) {

            return $this->errorResponse("error".$e->getMessage());

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
     * Update the specified post in storage.
     *
     * @param \App\Http\Requests\PostEditRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(PostEditRequest $request, string $uuid)
    {
        // Validate the request data
        $validated = validator($request->all());

        if($validated->fails()){
            return $this->validationError($validated,'messages.validation.failed');
        }

        // Array to store paths of uploaded images (if any)
        $uploadedImages = [];

        // Handle file uploads if images are present in the request
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $name = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('uploads', $name, 'public');
                $uploadedImages[] = $name;
            }
        }

        try {
            
            $post = Post::where('uuid_column', $uuid)->first();
            $user = auth()->user();

            if (!$post) {

                $errorMessage="messages.post_messages.post_not_found";
                $statusCode=404;

            } elseif ($post->user_id !== $user->id) {

                $errorMessage="messages.post_messages.user_not_owns";
                $statusCode=403;

            } else {
                // Retrieve the existing images array from the database
                $existingImages = $post->images ?? [];
                $updatedImages = $existingImages;

                // If 'delete_images' is provided then removing them from older ones
                if ($request->has('delete_images')) {
                    $deleteImages = $request->input('delete_images', []);
                    if (!is_array($deleteImages)) {
                        $deleteImages = [$deleteImages];
                    }
                    $updatedImages = array_diff($updatedImages, $deleteImages);
                }

                if (!is_array($updatedImages)) {
                    $updatedImages = [$updatedImages];
                }
                // Merge old and new images
                $mergedImages = array_merge($updatedImages, $uploadedImages);
                $post->update(array_merge($request->all(), ['images' => $mergedImages]));

            }
            
            return isset($errorMessage)?$this->errorResponse($errorMessage, $statusCode):$this->successResponse($post, 'messages.post_messages.update', 200);

        } catch (\Exception $e) {

            return $this->errorResponse("error".$e->getMessage());

        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
