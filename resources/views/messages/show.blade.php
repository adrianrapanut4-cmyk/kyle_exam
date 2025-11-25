@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg card-shadow overflow-hidden">
    <div class="p-4 border-b">
        <a href="{{ route('messages.index') }}" class="text-blue-600">← Back</a>
        <h2 class="text-xl font-semibold mt-2">{{ $user->name }}</h2>
    </div>

    <div class="p-4 h-96 overflow-y-auto">
        <div class="space-y-4">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs {{ $msg->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-slate-100' }} p-3 rounded-lg">
                        @if($msg->image_path)
                            <img src="{{ asset('storage/' . $msg->image_path) }}" alt="message" class="rounded max-h-48 mb-2" />
                        @endif
                        @if($msg->content)
                            <p>{{ $msg->content }}</p>
                        @endif
                        <div class="text-xs {{ $msg->sender_id === auth()->id() ? 'text-blue-100' : 'text-slate-500' }}">{{ $msg->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="p-4 border-t">
        <form method="POST" action="{{ route('messages.store', $user) }}" enctype="multipart/form-data">
            @csrf
            <div class="flex items-end space-x-2">
                <input type="text" name="content" placeholder="Type a message..." class="flex-1 border rounded-lg p-2" />
                <label class="cursor-pointer">
                    <input type="file" name="image" accept="image/*" class="hidden" />
                    📷
                </label>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Send</button>
            </div>
        </form>
    </div>
</div>
@endsection
