@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Home Feed</h2>

    <p>Welcome, {{ auth()->user()->name }}! This is a placeholder for the tweets feed.</p>

    <div class="mt-4">
        <a href="#" class="text-blue-600">Create a tweet</a>
    </div>
</div>
@endsection
