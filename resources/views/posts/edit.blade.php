<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
            
                <!-- Title Input -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="title" id="title" value="{{ $post->title }}" class="mt-1 p-2 border w-full rounded-md">
                </div>
            
                <!-- Body Textarea -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">Body:</label>
                    <textarea name="body" id="body" class="mt-1 p-2 border w-full rounded-md h-40">{{ $post->body }}</textarea>
                </div>
            
                <!-- Category Select -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category:</label>
                    <select name="category_id" id="category_id" class="mt-1 p-2 border w-full rounded-md">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            
                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
