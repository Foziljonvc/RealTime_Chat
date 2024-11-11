<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('chat', compact('users'));
    }

    public function show($userId): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $users = User::where('id', '!=', auth()->id())->get();
        $selectedUser = User::findOrFail($userId);

        $messages = Message::where(function($query) use ($userId) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $userId);
        })->orWhere(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', auth()->id());
        })->orderBy('created_at', 'asc')->get();

        return view('chat', compact('users', 'selectedUser', 'messages'));
    }

    public function send(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'message' => $validated['message']
        ]);

        return redirect()->route('chat.show', $validated['receiver_id']);
    }

}
