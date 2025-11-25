@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-slate-900 rounded-lg overflow-hidden card-shadow">
    <div class="h-36 bg-gradient-to-r from-sky-500 to-violet-600"></div>
    <div class="p-6">
        <div class="flex items-center space-x-4 -mt-12 justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-24 h-24 rounded-full bg-white dark:bg-slate-800 flex items-center justify-center text-2xl font-bold text-slate-700 dark:text-slate-300 overflow-hidden border-4 border-white">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                    @else
                        {{ strtoupper(substr($user->name,0,1)) }}
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</h2>
                    <div class="text-sm muted">Joined {{ $user->created_at->toFormattedDateString() }}</div>
                </div>
            </div>

            @auth
                @if(auth()->id() === $user->id)
                    <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-lg bg-blue-500 text-white">✏️ Edit Account</a>
                @else
                    <div class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('users.follow', $user) }}">
                            @csrf
                            <button class="px-4 py-2 rounded-lg text-white {{ auth()->user()->isFollowing($user) ? 'bg-slate-500' : 'btn-primary' }}">
                                {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                            </button>
                        </form>
                        <a href="{{ route('messages.show', $user) }}" class="px-4 py-2 rounded-lg bg-blue-500 text-white">💬 Message</a>
                    </div>
                @endif
            @endauth
        </div>

        @if($user->bio)
            <div class="mt-3 p-3 bg-slate-50 dark:bg-slate-800 rounded text-slate-800 dark:text-slate-100">{{ $user->bio }}</div>
        @endif

        <div class="mt-4 flex space-x-6">
            <div>
                <div class="text-sm muted">Tweets</div>
                <div class="font-semibold text-lg text-slate-900 dark:text-slate-100">{{ $tweetCount }}</div>
            </div>
            <div>
                <div class="text-sm muted">Followers</div>
                <div class="font-semibold text-lg text-slate-900 dark:text-slate-100">{{ $user->followers()->count() }}</div>
            </div>
            <div>
                <div class="text-sm muted">Following</div>
                <div class="font-semibold text-lg text-slate-900 dark:text-slate-100">{{ $user->following()->count() }}</div>
            </div>
            <div>
                <div class="text-sm muted">Likes received</div>
                <div class="font-semibold text-lg text-slate-900 dark:text-slate-100">{{ $likesReceived }}</div>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="font-semibold mb-3 text-slate-900 dark:text-slate-100">Recent Tweets</h3>
            <div class="space-y-3">
                @foreach ($tweets as $tweet)
                    <article class="bg-slate-50 dark:bg-slate-800 p-3 rounded">
                        <div class="text-sm muted">{{ $tweet->created_at->diffForHumans() }}</div>
                        <div class="mt-1 text-slate-800 dark:text-slate-100">{{ $tweet->content }}</div>
                        <div class="text-sm muted mt-2">{{ $tweet->likes->count() }} likes</div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
