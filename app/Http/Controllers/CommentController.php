<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Tweet;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Tweet $tweet)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'tweet_id' => $tweet->id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment posted!');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted!');
    }
}
