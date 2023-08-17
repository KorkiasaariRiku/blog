<?php
namespace App\Http\Controllers;

use App\Http\Requests\PostDeleteRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostIndexRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of posts with optional filtering.
     *
     * @param \App\Http\Requests\PostIndexRequest $request
     * @return \Illuminate\View\View
     */
    public function index(PostIndexRequest $request)
    {
        // Retrieve category ID and search term from the request
        $category_id = $request->input('category_id');
        $search = $request->input('search');
        
        // Retrieve filtered posts and categories
        $posts = $this->getFilteredPosts($category_id, $search);
        $categories = Category::all();

        // Load the 'posts.index' view with data
        return view('posts.index', compact('posts', 'categories', 'category_id', 'search'));
    }
    
    /**
     * Display a listing of posts by a specific user with optional filtering.
     *
     * @param \App\Models\User $user
     * @param \App\Http\Requests\PostIndexRequest $request
     * @return \Illuminate\View\View
     */
    public function indexByUser(User $user, PostIndexRequest $request)
    {
        // Retrieve category ID and search term from the request
        $category_id = $request->input('category_id');
        $search = $request->input('search');
        
        // Retrieve filtered posts for the specified user and categories
        $posts = $this->getFilteredPosts($category_id, $search, $user);
        $categories = Category::all();

        // Load the 'posts.index' view with data
        return view('posts.index', compact('posts', 'categories', 'category_id', 'search', 'user'));
    }
    
    /**
     * Get filtered posts based on category, search term, and optionally user.
     *
     * @param int|null $category_id
     * @param string|null $search
     * @param \App\Models\User|null $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private function getFilteredPosts($category_id, $search, $user = null)
    {
        // Define the base query depending on whether a user is specified
        $postsQuery = $user ? $user->posts() : Post::query();
        
        // Apply category filter if specified
        if ($category_id) {
            $postsQuery->whereCategory($category_id);
        }

        // Apply search filter if specified
        if ($search) {
            $postsQuery->whereSearch($search);
        }
        
        // Retrieve and paginate filtered posts, ordering them by latest
        return $postsQuery->latest()->paginate(6);
    }
    
    /**
     * Show the form for creating a new post resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retrieve all categories and load the 'posts.create' view with data
        return view('posts.create', [
            'categories' => Category::all(),
            'category_id' => null,
        ]);
    }  

    /**
     * Store a newly created post resource in storage.
     *
     * @param \App\Http\Requests\PostCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostCreateRequest $request)
    {
        // Create a new post instance and populate its attributes
        $post = new Post;
        $post->title = $request->title;
        $post->body = $request->body;
        $post->user_id = $request->user()->id;
        $post->category_id = $request->category_id;
        $post->save();
    
        // Redirect to the post's show page after successful creation
        return redirect()->route('posts.show', ['post' => $post->id])->with('success', 'Post created successfully.');
    }    

    /**
     * Display the specified post resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\View\View
     */
    public function show(Post $post, Request $request)
    {
        // Get the sorting parameter from the request (default to 'newest')
        $sort = $request->input('sort', 'newest');
    
        // Load the post along with its comments and sort the comments
        $post->load(['comments' => function ($query) use ($sort) {
            if ($sort === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
            $query->with('user'); // Eager load user information
        }]);
    
        // Pass the post and sort data to the view
        return view('posts.show', compact('post', 'sort'));
    }
    
    /**
     * Show the form for editing the specified post resource.
     *
     * @param \App\Models\Post $post
     * @return \Illuminate\View\View
     */
    public function edit(Post $post)
    {
        // Authorize the user's ability to update the post
        $this->authorize('update', $post);

        // Retrieve all categories and load the 'posts.edit' view with data
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified post resource in storage.
     *
     * @param \App\Http\Requests\PostUpdateRequest $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        // Update the post's data using Eloquent fill and save methods
        $post->fill([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
        ]);
        $post->save();

        // Redirect to the index page after successful update
        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    /**
     * Remove the specified post resource from storage.
     * 
     * @param \App\Http\Requests\PostDeleteRequest $request
     * @param \App\Models\Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PostDeleteRequest $request, Post $post)
    {
        // Delete the post using the model instance directly
        $post->delete();
    
        // Redirect to the index page after successful deletion
        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
