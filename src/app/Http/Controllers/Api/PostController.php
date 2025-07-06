<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Http\Requests\StorePostRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Resources\PostResource;
use App\Traits\HandlesImageUpload;

class PostController extends Controller
{
    use AuthorizesRequests, HandlesImageUpload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['author', 'categories', 'tags'])->latest()->paginate(10);

        return PostResource::collection($posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $this->authorize('create', Post::class);
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'posts');
        }

        $data['author_id'] = $request->user()->id;

        $post = Post::create($data);

        if (!empty($data['categories'])) {
            $post->categories()->sync($data['categories']);
        }

        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return response()->json([
            'message' => 'Post created successfully',
            'post'    => $post->load(['categories', 'tags']),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['author', 'categories', 'tags']);

        return new PostResource($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                $this->deleteImage($post->featured_image);
            }

            $data['featured_image'] = $this->uploadImage($request->file('image'), 'posts');
        }

        $post->update($data);

        $post->categories()->sync($data['categories'] ?? []);
        $post->tags()->sync($data['tags'] ?? []);

        return response()->json([
            'message' => 'Post updated',
            'post' => $post->load(['categories', 'tags']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
