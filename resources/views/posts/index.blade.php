<x-app-layout>
    <!-- Header section -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Search bar and category filter -->
        <div class="mb-4 flex space-x-4">
            <!-- Search Form -->
            <form action="{{ isset($user) ? route('posts.indexByUser', ['user' => $user->id]) : route('posts.index') }}" method="GET" class="flex w-2/5">
                <input type="hidden" name="category_id" value="{{ $category_id }}">
                <input type="text" name="search" placeholder="Search by title and body" class="p-2 border rounded-l-md w-full focus:outline-none focus:ring focus:border-blue-300 placeholder-gray-400">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600 focus:outline-none">
                    Search
                </button>
            </form>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category:</label>
                
                <!-- Loop through categories to create filter buttons -->
                @foreach ($categories as $category)
                    <form action="{{ isset($user) ? route('posts.indexByUser', ['user' => $user->id]) : route('posts.index') }}" method="GET" class="inline">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <button type="submit" name="category_id" value="{{ $category->id }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 @if($category_id == $category->id) bg-blue-600 @endif">{{ $category->name }}</button>
                    </form>
                @endforeach
                
                <!-- Display a link to clear the filter and search -->
                @if ($category_id !== null || $search !== null)
                    @if(isset($user)) <!-- Check if $user is defined -->
                    <a href="{{ route('posts.indexByUser', ['user' => $user->id]) }}" class="ml-2 text-blue-500">Clear Filters</a>
                    @else
                    <a href="{{ route('posts.index') }}" class="ml-2 text-blue-500">Clear Filters</a>
                    @endif
                @endif
            </div>
            <!-- Link to create a new post -->
    <a href="{{ route('posts.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 focus:outline-none">Create New Post</a>
        </div>

        <!-- Display the List of Posts -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <div class="bg-white shadow-md p-6 rounded-lg">
                    <!-- Post Title -->
                    <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                    <!-- Post Body -->
                    <p class="text-gray-600">{{ Str::limit($post->body, 150) }}</p>
                    <p class="mt-2 text-black-600">
                        Posted by:
                        <!-- Check if the post has a user -->
                        @if ($post->user)
                            <a href="{{ route('posts.indexByUser', ['user' => $post->user->id, 'category_id' => $category_id, 'search' => $search]) }}" class="text-blue-500 hover:underline">{{ $post->user->name }}</a>
                        @endif
                    </p>                   
                    <!-- Display Category Name -->
                    <p class="mt-2 text-black-600">{{ $post->category->name }}</p>
                    <!-- Read More Link -->
                    <div class="flex justify-between items-center mt-4">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="text-blue-500 hover:underline">Read More</a>
                        <!-- Post Created Date -->
                        <span class="text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $posts->appends(['category_id' => $category_id, 'search' => $search])->links() }}
    </div>
</x-app-layout>
