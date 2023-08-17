<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Post') }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <form action="{{ route('posts.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Title Input -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 p-2 border rounded-md w-full" required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body Textarea -->
                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">Content:</label>
                    <textarea name="body" id="body" class="mt-1 p-2 border rounded-md w-full h-40" required>{{ old('body') }}</textarea>
                    @error('body')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Selection -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category:</label>
                    <select name="category_id" id="category" class="mt-1 p-2 border rounded-md w-full" required>
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if($category_id == $category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
