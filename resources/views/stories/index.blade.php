@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg card-shadow p-6">
        <h2 class="text-2xl font-semibold">My Day / Stories</h2>

        <form method="POST" action="{{ route('stories.store') }}" enctype="multipart/form-data" class="mt-4">
            @csrf
            <div class="border-2 border-dashed rounded-lg p-6 text-center">
                <input type="file" name="image" accept="image/*" required class="hidden" id="story-image" />
                <label for="story-image" class="cursor-pointer">
                    <div class="text-4xl mb-2">📸</div>
                    <div class="font-semibold">Upload story image</div>
                    <div class="text-sm muted">Click to choose image</div>
                </label>
                <div id="storyImagePreview" class="mt-4"></div>
            </div>

            <textarea name="caption" maxlength="280" placeholder="Add caption (optional)" class="w-full border rounded-lg p-2 mt-4"></textarea>

            <button type="submit" class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg">Post Story</button>
        </form>
    </div>

    <div>
        <h3 class="text-xl font-semibold mb-4">Your Stories</h3>
        <div class="grid grid-cols-2 gap-4">
            @forelse($stories as $story)
                <div class="relative rounded-lg overflow-hidden card-shadow">
                    <img src="{{ asset('storage/' . $story->image_path) }}" alt="story" class="w-full h-64 object-cover" />
                    <div class="absolute inset-0 bg-black bg-opacity-30 p-3 flex flex-col justify-between">
                        @if($story->caption)
                            <div class="text-white text-sm">{{ $story->caption }}</div>
                        @endif
                        <div class="text-white text-xs">{{ $story->created_at->diffForHumans() }}</div>
                    </div>
                    <form method="POST" action="{{ route('stories.destroy', $story) }}" class="absolute top-2 right-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-sm">Delete</button>
                    </form>
                </div>
            @empty
                <p class="text-sm muted col-span-2">No stories yet</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
