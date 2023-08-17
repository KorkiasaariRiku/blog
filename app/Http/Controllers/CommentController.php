<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\CommentDeleteRequest;
use App\Http\Requests\CommentStoreRequest;
use App\Http\Requests\SortCommentsRequest;

class CommentController extends Controller
{
    /**
     * Display comments for a specific post.
     *
     * @param  \App\Models\Post  $post
     * @param  \App\Http\Requests\SortCommentsRequest  $request
     * @return \Illuminate\View\View
     */
    public function show(Post $post, SortCommentsRequest $request)
    {
        // Get the sorting parameter from the request (default to 'newest')
        $sort = $request->input('sort', 'newest');
    
        // Load comments for the post with the specified sorting
        $this->loadCommentsWithSorting($post, $sort);

        // Return the 'posts.show' view with post and sorting data
        return view('posts.show', compact('post', 'sort'));
    }

    /**
     * Load comments for a specific post with the specified sorting.
     *
     * @param  \App\Models\Post  $post
     * @param  string  $sort
     * @return void
     */
    private function loadCommentsWithSorting(Post $post, $sort)
    {
        // Load comments for the post along with user information
        $post->load(['comments' => function ($query) use ($sort) {
            $query->with('user')->when($sort === 'oldest', function ($query) {
                $query->oldest();
            }, function ($query) {
                $query->latest();
            });
        }]);
    }

    /**
     * Store a newly created comment for a specific post.
     *
     * @param  \App\Http\Requests\CommentStoreRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CommentStoreRequest $request, Post $post)
    {
        // Create a new comment instance with the provided body
        $comment = new Comment([
            'body' => $request->body,
        ]);

        // Associate the comment with the authenticated user and the post
        $comment->user()->associate(auth()->user());
        $comment->post()->associate($post);
        $comment->save();

        // Redirect back to the post's page with a success message
        return back()->with('success', 'Comment added successfully.');
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  \App\Http\Requests\CommentDeleteRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CommentDeleteRequest $request, Comment $comment)
    {
        // Delete the specified comment
        $comment->delete();
    
        // Redirect back to the post's page with a success message
        return back()->with('success', 'Comment deleted successfully.');
    }
}
