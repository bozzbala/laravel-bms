<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Blog API",
 *     description="Документация для API блога"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Local API server"
 * )
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     title="Post",
 *     required={"id", "title", "content", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Название поста"),
 *     @OA\Property(property="content", type="string", example="Контент поста"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published"}, example="draft"),
 *     @OA\Property(property="featured_image", type="string", format="url", nullable=true, example="http://localhost/storage/posts/image.jpg"),
 *     @OA\Property(property="author", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Admin User")
 *     ),
 *     @OA\Property(property="categories", type="array", @OA\Items(type="string", example="Laravel")),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string", example="PHP")),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-06T19:04:15.000000Z")
 * )
  * @OA\Schema(
 *     schema="Tag",
 *     type="object",
 *     title="Tag",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Laravel")
 * )
 * 
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Laravel")
 * )
*/


abstract class Controller
{
    //
}
