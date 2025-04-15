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

    // Menyimpan akses baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'user_id' => 'required|exists:users,id',
            'access_status' => 'required|in:ongoing,completed'
        ]);

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
}
