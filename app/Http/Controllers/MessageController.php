<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Message::where('sender_id', Auth::id())
            ->orWhere('recipient_id', Auth::id())
            ->latest()
            ->get()
            ->groupBy(function ($msg) {
                return $msg->sender_id === Auth::id() ? $msg->recipient_id : $msg->sender_id;
            });

        return view('messages.index', compact('conversations'));
    }

    public function show(User $user)
    {
        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', Auth::id())
              ->where('recipient_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
              ->where('recipient_id', Auth::id());
        })->latest()->get();

        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        if (!$data['content'] && !$request->hasFile('image')) {
            return back();
        }

        $message = new Message([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id,
            'content' => $data['content'] ?? null,
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('messages', 'public');
            $message->image_path = $path;
        }

        $message->save();

        return redirect()->route('messages.show', $user);
    }
}
