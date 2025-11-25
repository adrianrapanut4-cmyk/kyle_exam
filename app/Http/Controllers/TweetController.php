<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\TweetImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function index(Request $request)
    {
        $query = Tweet::with(['user', 'likes'])->latest();

        // Search functionality
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where('content', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
        }

        $tweets = $query->get();
        return view('tweets.index', compact('tweets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        $tweet = Tweet::create([
            'user_id' => Auth::id(),
            'content' => $data['content'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tweets', 'public');
            $tweet->images()->create(['image_path' => $path]);
        }

        return redirect()->route('tweets.index');
    }

    public function update(Request $request, Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'content' => 'required|string|max:280',
        ]);

        $tweet->update(['content' => $data['content']]);

        return redirect()->route('tweets.index');
    }

    public function destroy(Tweet $tweet)
    {
        if ($tweet->user_id !== Auth::id()) {
            abort(403);
        }

        $tweet->delete();

        return redirect()->route('tweets.index');
    }
}
