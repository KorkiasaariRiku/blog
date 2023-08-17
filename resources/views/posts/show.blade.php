<x-app-layout>
    <!-- Header Section -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <!-- Post Content Section -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h1 class="text-3xl font-semibold mb-4">{{ $post->title }}</h1>
            <p class="text-gray-600">{{ $post->body }}</p>
            <div class="mt-6 flex items-center space-x-4">
                <!-- Edit Post Button (Visible to authorized users) -->
                @can('update', $post)
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                @endcan

                <!-- Delete Post Form (Visible to authorized users) -->
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="container mx-auto px-4 py-8">
        <h3 class="text-lg font-semibold mt-6">Comments</h3>

        <!-- Dropdown menu for sorting comments -->
        <div class="mt-4">
            <form action="{{ route('posts.show', ['post' => $post->id]) }}" method="GET" class="flex items-center space-x-2">
                <label for="sort" class="text-gray-600">Sort by:</label>
                <select id="sort" name="sort" class="border rounded-md px-2 py-1 w-24">
                    <option value="newest" @if($sort === 'newest') selected @endif>Newest</option>
                    <option value="oldest" @if($sort === 'oldest') selected @endif>Oldest</option>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white rounded-md px-4 py-1">
                    Apply
                </button>
            </form>
        </div>

        <!-- Display paginated comments for the post -->
        <div class="mt-4 space-y-4">
            @forelse ($post->comments as $comment)
                <div class="bg-white rounded-md p-6 shadow-md">
                    <div class="flex items-start space-x-4">
                        <div class="flex-grow">
                            <!-- Comment Body -->
                            <p class="text-gray-600">{{ $comment->body }}</p>
                            <!-- Comment Metadata -->
                            <p class="text-sm text-gray-500">
                                Posted by: {{ $comment->user ? $comment->user->name : 'Guest User' }}
                                on {{ $comment->created_at->format('M d, Y \a\t H:i') }}
                            </p>
                        </div>
                        <!-- Delete Comment Form (Visible to authorized users) -->
                        @can('delete', $comment)
                            <form action="{{ route('comments.destroy', ['comment' => $comment->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete Comment</button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <!-- No Comments Message -->
                <p class="text-gray-600">No comments yet.</p>
            @endforelse
        </div>
        
        <!-- Pagination links -->
        <div class="mt-4">
            {{ $post->comments()->paginate(5)->links() }}
        </div>

        <!-- Add Comment Form -->
        @auth
        <div class="mt-6">
            <form action="{{ route('comments.store', ['post' => $post->id]) }}" method="POST" class="space-y-2">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <!-- Comment Textarea -->
                <textarea name="body" class="mt-1 p-2 border rounded-md w-full" rows="3" placeholder="Add a comment..." required></textarea>
                <!-- Add Comment Button -->
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                    Add Comment
                </button>
            </form>
        </div>
        @else
            <!-- Not Authenticated Message -->
            <p class="mt-6 text-gray-500">Log in to add a comment.</p>
        @endauth
    </div>
</x-app-layout>
