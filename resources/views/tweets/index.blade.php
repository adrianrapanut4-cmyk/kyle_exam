@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-slate-900 p-6 rounded card-shadow">
        <div class="flex items-start space-x-4">
            <div class="w-12 h-12 bg-gradient-to-br from-slate-300 to-slate-400 rounded-full flex items-center justify-center text-slate-800 font-semibold overflow-hidden">
                @if(auth()->user()->profile_picture)
                    <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}
                @endif
            </div>
            <div class="flex-1">
                <form method="POST" action="{{ route('tweets.store') }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="content" rows="3" maxlength="280" data-char-count class="w-full border border-slate-100 dark:border-slate-700 rounded-lg p-3 text-slate-800 dark:text-slate-100 dark:bg-slate-800 resize-none" placeholder="What's happening?">{{ old('content') }}</textarea>

                    <div class="mt-2">
                        <label class="text-sm muted cursor-pointer">
                            <input type="file" name="image" accept="image/*" class="hidden" id="tweet-image" />
                            📷 Add image
                        </label>
                        <div id="tweet-image-preview" class="mt-2"></div>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <div class="text-sm muted"> <span data-char-remaining>280</span> characters remaining</div>
                        <button type="submit" class="px-4 py-2 rounded-lg text-white btn-primary">Tweet</button>
                    </div>

                    @if ($errors->has('content'))
                        <div class="text-red-600 mt-2">{{ $errors->first('content') }}</div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div>
        @foreach ($tweets as $tweet)
            <article class="bg-white dark:bg-slate-900 p-4 rounded-lg card-shadow mb-4 hover:shadow-lg transition-shadow border-b dark:border-slate-800">
                <div class="flex">
                    <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center font-semibold text-slate-700 dark:text-slate-300 overflow-hidden">
                        @if($tweet->user->profile_picture)
                            <img src="{{ asset('storage/' . $tweet->user->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                        @else
                            {{ strtoupper(substr($tweet->user->name,0,1)) }}
                        @endif
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <a href="{{ route('profile.show', $tweet->user) }}" class="font-semibold text-slate-800 dark:text-slate-100 hover:underline">{{ $tweet->user->name }}</a>
                                <div class="text-sm muted">{{ $tweet->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        <div class="mt-3 text-slate-800 dark:text-slate-100 whitespace-pre-wrap">{{ $tweet->content }}</div>

                        @if($tweet->images->count())
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                @foreach($tweet->images as $img)
                                    <img src="{{ asset('storage/' . $img->image_path) }}" alt="tweet image" class="rounded-lg" />
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-3 flex items-center space-x-4 text-sm">
                            <form method="POST" action="{{ route('tweets.like', $tweet) }}" class="inline">
                                @csrf
                                <button data-like-button type="button" class="flex items-center space-x-1 hover:text-red-500 transition">
                                    <span data-heart-icon class="text-lg" style="{{ $tweet->likes->where('user_id', auth()->id())->count() ? 'color: red;' : '' }}">{{ $tweet->likes->where('user_id', auth()->id())->count() ? '❤️' : '🤍' }}</span>
                                    <span class="text-black dark:text-white font-semibold" data-like-count>{{ $tweet->likes->count() }}</span>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('tweets.repost', $tweet) }}" class="inline">
                                @csrf
                                <button class="flex items-center space-x-1 hover:text-green-500 transition {{ $tweet->isRepostedBy(auth()->user()) ? 'text-green-500' : '' }}">
                                    <span>🔄</span>
                                    <span class="muted">{{ $tweet->reposts()->count() }}</span>
                                </button>
                            </form>

                            <button class="flex items-center space-x-1 hover:text-blue-500 transition toggle-comments">
                                <span>💬</span>
                                <span class="muted">{{ $tweet->comments()->count() }}</span>
                            </button>

                            @if (auth()->id() === $tweet->user_id)
                                <form method="POST" action="{{ route('tweets.destroy', $tweet) }}" onsubmit="return confirm('Delete this tweet?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Delete</button>
                                </form>

                                <form method="POST" action="{{ route('tweets.update', $tweet) }}" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="content" value="{{ $tweet->content }}" class="border rounded px-2 text-sm" />
                                    <button class="text-green-600 ml-2">Save</button>
                                </form>
                            @endif
                        </div>

                        <div class="comments-section mt-4 hidden">
                            <div class="space-y-3 mb-3 max-h-64 overflow-y-auto">
                                @foreach($tweet->comments()->latest()->get() as $comment)
                                    <div class="bg-slate-50 p-3 rounded">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <a href="{{ route('profile.show', $comment->user) }}" class="font-semibold text-sm text-slate-800 hover:underline">{{ $comment->user->name }}</a>
                                                <div class="text-xs muted">{{ $comment->created_at->diffForHumans() }}</div>
                                            </div>
                                            @if(auth()->id() === $comment->user_id)
                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete comment?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="text-red-600 text-xs">✕</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="text-sm text-slate-800 mt-1">{{ $comment->content }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <form method="POST" action="{{ route('comments.store', $tweet) }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="content" placeholder="Add a comment..." class="flex-1 text-sm px-2 py-1 rounded border border-slate-200" maxlength="500" />
                                <button type="submit" class="text-blue-500 text-sm font-semibold">Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
