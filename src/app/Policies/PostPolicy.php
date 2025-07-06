<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Post $post): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage_posts');
    }

    public function update(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('edit_posts') && $post->author_id === $user->id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->hasPermissionTo('delete_posts');
    }
}
