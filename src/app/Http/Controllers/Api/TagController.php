<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;

/**
 * @OA\Tag(
 *     name="Tags",
 *     description="CRUD операции с тегами"
 * )
 */
class TagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tags",
     *     summary="Список тегов",
     *     tags={"Tags"},
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Tag"))
     *     )
     * )
     */
    public function index()
    {
        return TagResource::collection(Tag::all());
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     summary="Создать тег",
     *     tags={"Tags"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="PHP")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Тег создан", @OA\JsonContent(ref="#/components/schemas/Tag")),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:tags']);
        return new TagResource(Tag::create(['name' => $request->name]));
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     summary="Получить тег по ID",
     *     tags={"Tags"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Тег найден", @OA\JsonContent(ref="#/components/schemas/Tag")),
     *     @OA\Response(response=404, description="Тег не найден")
     * )
     */
    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     summary="Обновить тег",
     *     tags={"Tags"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated Tag")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Тег обновлён", @OA\JsonContent(ref="#/components/schemas/Tag")),
     *     @OA\Response(response=422, description="Ошибка валидации")
     * )
     */
    public function update(Request $request, Tag $tag)
    {
        $request->validate(['name' => 'required|string|max:255|unique:tags,name,' . $tag->id]);
        $tag->update(['name' => $request->name]);
        return new TagResource($tag);
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     summary="Удалить тег",
     *     tags={"Tags"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Тег удалён"),
     *     @OA\Response(response=404, description="Тег не найден")
     * )
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
