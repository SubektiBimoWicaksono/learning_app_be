<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Carbon\Carbon; 
class ChatRoomController extends Controller
{

    public function createRoom(Request $request)
    {
        $request->validate([
            'mentor_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:users,id',
        ]);
    
        $chat = ChatRoom::where('mentor_id', $request->mentor_id)
                ->where('student_id', $request->student_id)
                ->first();
    
        if ($chat) {
            // Jika chat sudah ada, kembalikan data chat dengan relasi
            $chat->load('mentor', 'student');
            return response()->json([
                'message' => 'chat sudah ada',
                'data' => $chat,
            ], 200);
        }
    
        $chatRoom = ChatRoom::create([
            'mentor_id' => $request->mentor_id,
            'student_id' => $request->student_id,
        ]);
    
        $chatRoom->load('mentor', 'student'); // Muat relasi mentor dan student
    
        return response()->json([
            'message' => 'Chat room created',
            'data' => $chatRoom,
        ]);
    }
    public function myRooms(Request $request)
{
    $user = $request->user();

    $chatRooms = ChatRoom::with(['mentor', 'student'])
        ->with(['chats' => function ($query) {
            $query->latest()->limit(1); // Ambil pesan terakhir
        }])
        ->where('mentor_id', $user->id)
        ->orWhere('student_id', $user->id)
        ->get();

    // Tambahkan last_message dan last_message_time ke setiap chat room
    $chatRooms->each(function ($chatRoom) {
        $lastMessage = $chatRoom->chats->first();
        $chatRoom->last_message = $lastMessage ? $lastMessage->message : null; // Pastikan kolom 'content' sesuai
        $chatRoom->last_message_time = $lastMessage ? Carbon::parse($lastMessage->created_at)->format('d M Y, H:i') : null; // Format tanggal
        unset($chatRoom->chats); // Hapus properti chats untuk menjaga respons tetap bersih
    });

    return response()->json($chatRooms);
}
}
