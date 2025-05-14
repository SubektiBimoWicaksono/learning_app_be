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
            'attachment' => 'nullable|file',
        ]);
    
        $chat = new Chat();
        $chat->chat_room_id = $request->chat_room_id;
        $chat->user_id = auth()->id();
        $chat->message = $request->message;
    
        if ($request->hasFile('attachment')) {

            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . uniqid() . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('uploads/attachments'), $attachmentName);

        
            $chat->attachment ='uploads/attachments/' . $attachmentName;
        }
    
        $chat->save();
        $chat->load('user'); // Memuat user untuk frontend
    
        return response()->json(['message' => 'Chat sent successfully', 'data' => $chat]);
    }
    
    public function index($chatRoomId)
    {
        $chats = Chat::where('chat_room_id', $chatRoomId)
                    ->with('user')
                    ->orderBy('created_at')
                    ->get()
                    ->map(function ($chat) {
                        return [
                            'id' => $chat->id,
                            'user_id' => $chat->user_id,
                            'text' => $chat->message,
                            'isMe' => auth()->check() && auth()->id() === $chat->user_id,
                            'created_at' => $chat->created_at->format('H:i'),
                            'attachment' => $chat->attachment ? $chat->attachment : null,
                            'user' => [
                                'name' => $chat->user->name,
                                'email' => $chat->user->email,
                                'photo' => $chat->user->photo,
                            ],
                        ];
                    });
    
        return response()->json($chats);
    }
    
}
