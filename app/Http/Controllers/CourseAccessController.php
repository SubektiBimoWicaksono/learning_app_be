<?php

namespace App\Http\Controllers;

use App\Models\CourseAccess;
use Illuminate\Http\Request;

class CourseAccessController extends Controller
{
    // Menampilkan semua data akses course
    public function index()
    {
        return CourseAccess::with(['user', 'course'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'access_status' => 'required|in:ongoing,completed'
        ]);

        // Ambil user ID dari user yang login
        $userId = auth()->id();

        // Tambahkan user_id ke data validasi
        $validated['user_id'] = $userId;

        // Cegah duplikasi akses yang sama untuk course yang sama
        $existingAccess = \App\Models\CourseAccess::where('user_id', $userId)
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingAccess) {
            return response()->json(['message' => 'Access already exists'], 409);
        }

        $access = CourseAccess::create($validated);
        return response()->json(['message' => 'Access granted', 'data' => $access], 201);
    }


    // Menampilkan akses berdasarkan ID
    public function show($id)
    {
        $access = CourseAccess::with(['user', 'course'])->findOrFail($id);
        return $access;
    }

    // Update akses
    public function update(Request $request, $id)
    {
        $access = CourseAccess::findOrFail($id);
        $validated = $request->validate([
            'access_status' => 'required|in:ongoing,completed'
        ]);
        $access->update($validated);
        return response()->json(['message' => 'Access updated', 'data' => $access]);
    }

    // Hapus akses
    public function destroy($id)
    {
        $access = CourseAccess::findOrFail($id);
        $access->delete();
        return response()->json(['message' => 'Access deleted']);
    }

    // Mengecek apakah user sudah enroll di course tertentu
    public function isEnrolled(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $userId = auth()->id();
        $courseId = $request->course_id;

        $isEnrolled = CourseAccess::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->exists();

        return response()->json(['enrolled' => $isEnrolled]);
    }


    //     // Menampilkan semua course yang diakses oleh user (bisa difilter by status)
    // public function index(Request $request)
    // {
    //     $userId = Auth::id();
    //     $status = $request->query('status'); // optional filter: ongoing / completed

    //     $query = CourseAccess::with('course')
    //         ->where('user_id', $userId);

    //     if ($status) {
    //         $query->where('access_status', $status);
    //     }

    //     return response()->json($query->get());
    // }

    // // Enroll ke course (jika belum pernah)
    // public function enroll(Request $request)
    // {
    //     $request->validate([
    //         'course_id' => 'required|exists:courses,id',
    //     ]);

    //     $userId = Auth::id();
    //     $courseId = $request->course_id;

    //     $existing = CourseAccess::where('user_id', $userId)
    //         ->where('course_id', $courseId)
    //         ->first();

    //     if ($existing) {
    //         return response()->json([
    //             'message' => 'User already enrolled in this course.',
    //             'data' => $existing
    //         ], 200);
    //     }

    //     $access = CourseAccess::create([
    //         'user_id' => $userId,
    //         'course_id' => $courseId,
    //         'access_status' => 'ongoing',
    //     ]);

    //     return response()->json([
    //         'message' => 'Enrollment successful',
    //         'data' => $access
    //     ], 201);
    // }

    // // Update status course access (e.g. to 'completed')
    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'access_status' => 'required|in:ongoing,completed',
    //     ]);

    //     $access = CourseAccess::findOrFail($id);

    //     if ($access->user_id !== Auth::id()) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $access->access_status = $request->access_status;
    //     $access->save();

    //     return response()->json([
    //         'message' => 'Course status updated.',
    //         'data' => $access
    //     ]);
    // }

    // // Menampilkan detail akses course tertentu
    // public function show($id)
    // {
    //     $access = CourseAccess::with(['course'])
    //         ->where('id', $id)
    //         ->where('user_id', Auth::id())
    //         ->firstOrFail();

    //     return response()->json($access);
    // }
}
