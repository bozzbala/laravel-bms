<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/assign-role', function (Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);

        if (! $request->user()->can('manage_users')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $targetUser = User::find($request->user_id);
        $targetUser->syncRoles([$request->role]);

        return response()->json([
            'message' => 'Role assigned',
            'user' => $targetUser->only(['id', 'name', 'email']),
            'roles' => $targetUser->getRoleNames(),
        ]);
    });

    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tags', TagController::class);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function (Request $request) {
    return response()->json(['message' => 'Blog Management System API']);
});
