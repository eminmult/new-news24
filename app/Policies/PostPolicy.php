<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view posts list
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        // Admin and Editor can view all posts
        if ($user->isAdmin() || $user->isEditor()) {
            return true;
        }

        // Author can only view their own posts
        return $user->id === $post->author_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All roles can create posts
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        // Admin and Editor can update all posts
        if ($user->isAdmin() || $user->isEditor()) {
            return true;
        }

        // Author can only update their own posts
        return $user->id === $post->author_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        // Admin and Editor can delete all posts
        if ($user->isAdmin() || $user->isEditor()) {
            return true;
        }

        // Author can only delete their own posts
        return $user->id === $post->author_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        // Admin and Editor can restore all posts
        if ($user->isAdmin() || $user->isEditor()) {
            return true;
        }

        // Author can only restore their own posts
        return $user->id === $post->author_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        // Only Admin can permanently delete posts
        return $user->isAdmin();
    }
}
