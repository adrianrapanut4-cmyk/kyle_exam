<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');
        $users = User::query();

        if ($search) {
            $users->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $users->where('id', '!=', auth()->id())
                       ->latest()
                       ->paginate(12);

        return view('users.index', compact('users', 'search'));
    }
}
