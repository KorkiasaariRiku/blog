<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true; // Allow administrators to perform any action
        }
    }
    
    public function delete(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id || $user->isAdmin();
    }
}
