<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'chat_room_id' => 'required|exists:chat_rooms,id',
            'message' => 'required_without:attachment|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $chat = new Chat();
        $chat->chat_room_id = $request->chat_room_id;
        $chat->user_id = auth()->id();
        $chat->message = $request->message;

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $chat->attachment = $path;
        }

        $chat->save();

        return response()->json(['message' => 'Chat sent successfully', 'data' => $chat]);
    }

    public function index($chatRoomId)
    {
        $chats = Chat::where('chat_room_id', $chatRoomId)->with('user')->orderBy('created_at')->get();

        return response()->json($chats);
    }
}
