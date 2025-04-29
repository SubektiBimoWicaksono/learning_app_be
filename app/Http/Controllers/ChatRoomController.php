<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;

class ChatRoomController extends Controller
{
    public function createRoom(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
        ]);

        $chatRoom = ChatRoom::firstOrCreate([
            'mentor_id' => $request->mentor_id,
            'student_id' => $request->student_id,
        ]);

        return response()->json(['message' => 'Chat room created', 'data' => $chatRoom]);
    }

    public function myRooms()
    {
        $userId = auth()->id();

        $rooms = ChatRoom::where('mentor_id', $userId)
            ->orWhere('student_id', $userId)
            ->with(['mentor', 'student'])
            ->get();

        return response()->json($rooms);
    }
}
