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
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Получить список постов",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Список постов",
     *         @OA\JsonContent(type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Post"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        $posts = Post::with(['author', 'categories', 'tags'])->latest()->paginate(10);
        return PostResource::collection($posts);
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Создать новый пост",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "content", "status"},
     *                 @OA\Property(property="title", type="string", example="Новый пост"),
     *                 @OA\Property(property="content", type="string", example="Контент поста"),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published"}, example="draft"),
     *                 @OA\Property(property="categories", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="featured_image", type="file")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Пост успешно создан",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response=403, description="Недостаточно прав")
     * )
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

        $post->categories()->sync($data['categories'] ?? []);
        $post->tags()->sync($data['tags'] ?? []);

        return response()->json([
            'message' => 'Post created successfully',
            'post'    => $post->load(['categories', 'tags']),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Получить пост по ID",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Пост",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function show(Post $post)
    {
        $post->load(['author', 'categories', 'tags']);
        return new PostResource($post);
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Обновить пост",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="content", type="string"),
     *                 @OA\Property(property="status", type="string", enum={"draft", "published"}),
     *                 @OA\Property(property="categories", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="tags", type="array", @OA\Items(type="integer")),
     *                 @OA\Property(property="featured_image", type="file")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Пост обновлен"),
     *     @OA\Response(response=403, description="Недостаточно прав"),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function update(StorePostRequest $request, Post $post)
    {
        $this->authorize('update', $post);
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                $this->deleteImage($post->featured_image);
            }

            $data['featured_image'] = $this->uploadImage($request->file('featured_image'), 'posts');
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
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Удалить пост",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID поста",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Пост удалён"),
     *     @OA\Response(response=403, description="Недостаточно прав"),
     *     @OA\Response(response=404, description="Пост не найден")
     * )
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
