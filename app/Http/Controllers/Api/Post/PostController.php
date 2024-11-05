<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use App\Traits\JsonResponseTrait;
use App\Http\Requests\PostAddRequest;
use App\Http\Requests\PostEditRequest;
use Illuminate\Support\Facades\Auth;


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
            $posts = Post::where('status', "1")->get();

            if ($posts->isEmpty()) {
                return $this->successResponse([], 'messages.post_messages.not_found', 404);
            }

            return $this->successResponse($posts, 'messages.post_messages.fetched', 200);
        } catch (\Exception $e) {

            return $this->errorResponse("error" . $e->getMessage());

        }
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

        if ($validated->fails()) {
            return $this->validationError($validated, 'messages.validation.failed');
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
            $user = auth()->user();
            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'images' => $uploadedImages,
                'user_id' => $user->id,
                'post_type' => $request->post_type
            ]);

            return $this->successResponse($post, 'messages.post_messages.success', 201);

        } catch (\Exception $e) {

            return $this->errorResponse("error" . $e->getMessage());

        }
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

        if ($validated->fails()) {
            return $this->validationError($validated, 'messages.validation.failed');
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

                $errorMessage = "messages.post_messages.post_not_found";
                $statusCode = 404;

            } elseif ($post->user_id !== $user->id) {

                $errorMessage = "messages.post_messages.user_not_owns";
                $statusCode = 403;

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

            return isset($errorMessage) ? $this->errorResponse($errorMessage, $statusCode) : $this->successResponse($post, 'messages.post_messages.update', 200);

        } catch (\Exception $e) {

            return $this->errorResponse("error" . $e->getMessage());

        }
    }


    /**
     * Summary of addComment
     * @param \App\Http\Requests\CommentRequest $request
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function addComment(CommentRequest $request, string $uuid)
    {

        try {
            $request->validated();
            $post = Post::where('uuid_column', $uuid)->firstOrFail();
            $comment = $post->comments()->create([
                'user_id' => Auth::id(),
                'content' => $request->content,
            ]);
            $data = [
                "id" => $comment['id'],
                "post_id" => $comment['post_id'],
                "user_id" => $comment['user_id'],
                "comment" => $request->content,
                "created_at" => $comment['created_at'],
                "updated_at" => $comment['updated_at']
            ];
            return $this->successResponse($data, 'messages.comment_messages', 201);
        } catch (\Exception $e) {
            return $this->errorResponse("error" . $e->getMessage(), 500);
        }
    }

    public function like_post(string $uuid)
    {
        $user=auth()->user();
        try{
            $post=Post::where('uuid_column',$uuid)->first();
            $liked=Like::where('post_id',$post->id)->where('user_id',$user->id)->exists();
            if($liked){
                $likes_count = Like::where('post_id', $post->id)->count();
                    
                return $this->successResponse(['likes_count' => $likes_count],'messages.post_messages.post_like');
            }else{
                Like::create([
                    'post_id'=>$post->id,
                    'user_id'=>$user->id
                ]);
                $likes_count = Like::where('post_id', $post->id)->count();
                return $this->successResponse(['likes_count' => $likes_count],'messages.post_messages.post_like');
            }
        } catch (\Exception $e){
            return $this->errorResponse("error".$e->getMessage());
        }
    }
}
