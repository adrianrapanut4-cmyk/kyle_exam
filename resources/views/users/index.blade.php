@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-6 rounded-lg card-shadow">
        <h2 class="text-2xl font-semibold mb-4">Discover Users</h2>

        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="relative">
                <input type="text" name="q" value="{{ $search }}" placeholder="Search users by name..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300" />
                <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"></path></svg>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($users as $user)
            <div class="bg-white p-6 rounded-lg card-shadow">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-sky-400 to-violet-500 flex items-center justify-center text-white font-semibold overflow-hidden">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                        @else
                            {{ strtoupper(substr($user->name,0,1)) }}
                        @endif
                    </div>
                    <div class="text-sm muted">{{ $user->tweets()->count() }} tweets</div>
                </div>

                <a href="{{ route('profile.show', $user) }}" class="block">
                    <h3 class="font-semibold text-slate-900 hover:underline">{{ $user->name }}</h3>
                    <p class="text-sm muted">{{ $user->email }}</p>
                </a>

                <div class="mt-3 text-sm muted">{{ $user->followers()->count() }} followers</div>

                <div class="mt-4 flex space-x-2">
                    <form method="POST" action="{{ route('users.follow', $user) }}" class="flex-1">
                        @csrf
                        <button class="w-full px-3 py-2 rounded-lg text-sm text-white {{ auth()->user()->isFollowing($user) ? 'bg-slate-500' : 'btn-primary' }}">
                            {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                        </button>
                    </form>
                    <a href="{{ route('messages.show', $user) }}" class="flex-1 px-3 py-2 rounded-lg bg-blue-500 text-white text-sm text-center">💬</a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-muted">{{ $search ? 'No users found matching your search' : 'No users to discover' }}</p>
            </div>
        @endforelse
    </div>

    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
