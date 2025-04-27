<?php

namespace App\Http\Controllers;

use App\Models\VideoAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoAccessController extends Controller
{
    // GET: Lihat semua akses video user yang login
    public function index()
    {
        $userId = Auth::id();

        $accesses = VideoAccess::with('course')
            ->where('user_id', $userId)
            ->get();

        return response()->json($accesses);
    }

    // POST: Simpan akses video baru atau update status akses
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'access_status' => 'required|string' // contoh: started, in_progress, completed
        ]);

        $userId = Auth::id();

        $access = VideoAccess::updateOrCreate(
            ['user_id' => $userId, 'course_id' => $request->course_id],
            ['access_status' => $request->access_status]
        );

        return response()->json([
            'message' => 'Akses video berhasil disimpan/diupdate',
            'data' => $access
        ]);
    }

    // GET: Ambil akses tertentu berdasarkan course_id
    public function show($course_id)
    {
        $userId = Auth::id();
        $access = VideoAccess::where('user_id', $userId)
                    ->where('course_id', $course_id)
                    ->first();

        if (!$access) {
            return response()->json(['message' => 'Akses tidak ditemukan'], 404);
        }

        return response()->json($access);
    }

    public function destroy($course_id)
{
    $userId = Auth::id();

    $access = VideoAccess::where('user_id', $userId)
        ->where('course_id', $course_id)
        ->first();

    if (!$access) {
        return response()->json(['message' => 'Akses tidak ditemukan'], 404);
    }

    $access->delete();

    return response()->json(['message' => 'Akses berhasil dihapus']);
}

}
