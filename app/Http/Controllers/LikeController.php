<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Tweet $tweet, Request $request)
    {
        $userId = Auth::id();

        $existing = Like::where('user_id', $userId)->where('tweet_id', $tweet->id)->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create(['user_id' => $userId, 'tweet_id' => $tweet->id]);
            $liked = true;
        }

        if ($request->expectsJson()) {
            return response()->json(['liked' => $liked, 'count' => $tweet->likes()->count()]);
        }

        return back();
    }
}
