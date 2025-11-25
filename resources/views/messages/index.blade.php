@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg card-shadow p-4">
    <h2 class="text-2xl font-semibold">Direct Messages</h2>

    <div class="mt-4 space-y-2">
        @forelse($conversations as $userId => $msgs)
            @php
                $otherUser = $msgs->first()->sender_id === auth()->id() 
                    ? $msgs->first()->recipient 
                    : $msgs->first()->sender;
            @endphp
            <a href="{{ route('messages.show', $otherUser) }}" class="block p-3 rounded border border-slate-100 hover:bg-slate-50">
                <div class="font-semibold">{{ $otherUser->name }}</div>
                <div class="text-sm muted truncate">{{ $msgs->first()->content ?? '📷 Image' }}</div>
            </a>
        @empty
            <p class="text-sm muted">No conversations yet</p>
        @endforelse
    </div>
</div>
@endsection
