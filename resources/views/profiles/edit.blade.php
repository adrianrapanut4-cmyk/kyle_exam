@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-8 rounded-lg card-shadow">
        <h2 class="text-3xl font-bold mb-6">Edit Account</h2>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 p-4 rounded-lg mb-4">
                <ul class="list-disc list-inside text-red-700 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label for="profile_picture" class="block text-sm font-semibold text-slate-700 mb-2">Profile Picture</label>
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-sky-400 to-violet-500 flex items-center justify-center text-white font-semibold text-2xl overflow-hidden">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="profile" class="w-full h-full object-cover" />
                        @else
                            {{ strtoupper(substr($user->name,0,1)) }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="block w-full text-sm px-3 py-2 border border-slate-200 rounded-lg" id="profilePictureInput" />
                        <div id="profilePicturePreview" class="mt-2"></div>
                        <p class="text-xs muted mt-1">JPG, PNG, GIF or WebP. Max 5MB.</p>
                    </div>
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300" required />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300" required />
            </div>

            <div>
                <label for="bio" class="block text-sm font-semibold text-slate-700 mb-1">Bio</label>
                <textarea id="bio" name="bio" rows="3" class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300">{{ old('bio', $user->bio) }}</textarea>
                <p class="text-xs muted mt-1">Max 500 characters</p>
            </div>

            <div class="border-t border-slate-200 pt-4 mt-6">
                <h3 class="font-semibold text-slate-700 mb-4">Change Password (Optional)</h3>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">New Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300" />
                    <p class="text-xs muted mt-1">Leave blank to keep current password. Minimum 8 characters.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1 mt-3">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-4 py-2 rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-sky-300" />
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Save Changes</button>
                <a href="{{ route('profile.show', auth()->user()) }}" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
