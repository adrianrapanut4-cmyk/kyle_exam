<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $tweets = $user->tweets()->with('likes')->latest()->get();
        $tweetCount = $tweets->count();
        $likesReceived = $tweets->reduce(function ($carry, $tweet) {
            return $carry + $tweet->likes->count();
        }, 0);

        return view('profiles.show', compact('user', 'tweets', 'tweetCount', 'likesReceived'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profiles.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $validated['profile_picture'] = $path;
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully!');
    }
}
