<?php

namespace App\Http\Controllers;

use App\Models\Repost;
use App\Models\Tweet;
use Illuminate\Http\Request;

class RepostController extends Controller
{
    public function store(Request $request, Tweet $tweet)
    {
        $existing = Repost::where('user_id', auth()->id())->where('tweet_id', $tweet->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Repost removed!');
        }

        Repost::create([
            'user_id' => auth()->id(),
            'tweet_id' => $tweet->id,
        ]);

        return back()->with('success', 'Reposted!');
    }
}
