<?php

namespace App\Http\Controllers;

use App\Models\CourseAccess;
use App\Models\Certificate;
use Illuminate\Http\Request;

class CourseAccessController extends Controller
{


    public function getStudentCourses($userId)
    {
        
    $accesses = \App\Models\CourseAccess::with('course', 'course.user','course.category')
        ->where('user_id', $userId)
        ->get();

    $ongoing = $accesses->where('access_status', 'ongoing')
        ->pluck('course')
        ->filter()
        ->values();

    $completed = $accesses->where('access_status', 'completed')
        ->pluck('course')
        ->filter()
        ->values();

    return response()->json([
        'ongoing' => $ongoing,
        'completed' => $completed,
    ]);
}
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

    public function generateCertificate($id)
    {
        $access = CourseAccess::with('course', 'user')->findOrFail($id);

        if ($access->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($access->access_status !== 'completed') {
            return response()->json(['message' => 'Course belum diselesaikan'], 400);
        }

        // Cek apakah sertifikat sudah pernah dibuat
        $existing = Certificate::where('course_access_id', $access->id)->first();
        if ($existing) {
            return response()->json([
                'message' => 'Sertifikat sudah pernah dibuat',
                'certificate' => $existing
            ]);
        }

        $certificate = Certificate::create([
            'user_id' => $access->user_id,
            'course_id' => $access->course_id,
            'course_access_id' => $access->id,
            'certificate_id' => 'CERT-' . strtoupper(uniqid()),
            'completed_at' => $access->updated_at,
        ]);

        return response()->json([
            'message' => 'Sertifikat berhasil digenerate',
            'certificate' => $certificate
        ]);
    }
}
