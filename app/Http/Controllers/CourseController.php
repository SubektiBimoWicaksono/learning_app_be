<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Tampilkan semua course
    public function index()
    {
        return response()->json(Course::with('user')->get());
    }

    // Tambah course baru
    public function store(Request $request)
{
    $user = auth()->user();

    // Cek apakah role-nya mentor
    if ($user->role !== 'mentor') {
        return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat category'], 403);
    }

    // Validasi data kecuali user_id
    $request->validate([
        'name'              => 'required|string|max:255',
        'desc'              => 'nullable|string',
        'media_full_access' => 'nullable|boolean',
        'level'             => 'nullable|string',
        'audio_book'        => 'nullable|boolean',
        'lifetime_access'   => 'nullable|boolean',
        'certificate'       => 'nullable|boolean',
        'image'             => 'nullable|string',
    ]);

    // Tambah course dengan user_id dari session login
    $course = Course::create([
        'name'              => $request->name,
        'desc'              => $request->desc,
        'user_id'           => $user->id,
        'media_full_access' => $request->media_full_access,
        'level'             => $request->level,
        'audio_book'        => $request->audio_book,
        'lifetime_access'   => $request->lifetime_access,
        'certificate'       => $request->certificate,
        'image'             => $request->image,
    ]);

    return response()->json([
        'message' => 'Course berhasil dibuat oleh mentor',
        'data' => $course
    ], 201);
}


    // Tampilkan satu course
    public function show($id)
    {
        $course = Course::with('user')->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        return response()->json($course);
    }

    // Update course
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        $course->update($request->all());

        return response()->json([
            'message' => 'Course berhasil diperbarui',
            'data' => $course
        ]);
    }

    // Hapus course
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course berhasil dihapus']);
    }
}
