<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $followingIds = $user->following()->pluck('following_id')->toArray();
        $followingIds[] = $user->id;

        $stories = Story::whereIn('user_id', $followingIds)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->latest()
            ->get();

        return view('stories.index', compact('stories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120',
            'caption' => 'nullable|string|max:280',
        ]);

        $path = $request->file('image')->store('stories', 'public');

        Story::create([
            'user_id' => Auth::id(),
            'image_path' => $path,
            'caption' => $data['caption'] ?? null,
            'expires_at' => now()->addHours(24),
        ]);

        return redirect()->route('stories.index');
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403);
        }

        $story->delete();

        return redirect()->route('stories.index');
    }
}
