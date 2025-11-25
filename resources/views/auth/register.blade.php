@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4">Create an account</h2>

    @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-200 rounded p-2" required />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-200 rounded p-2" required />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Password</label>
            <input name="password" type="password" class="mt-1 block w-full border-gray-200 rounded p-2" required />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Confirm Password</label>
            <input name="password_confirmation" type="password" class="mt-1 block w-full border-gray-200 rounded p-2" required />
        </div>

        <div>
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded">Register</button>
        </div>
    </form>
</div>
@endsection
