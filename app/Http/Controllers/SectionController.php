<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // GET - List all sections (optional: filter by course_id)
    public function index(Request $request)
    {
        $sections = Section::with('course', 'videos', 'quiz');

        if ($request->has('course_id')) {
            $sections->where('course_id', $request->course_id);
        }

        return response()->json($sections->get());
    }

    // POST - Create section
    public function store(Request $request)
    {

        $user = auth()->user();

        // Cek apakah role-nya mentor
        if ($user->role !== 'mentor') {
            return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat Section'], 403);
        }

        $request->validate([
            'name'      => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
        ]);

        $section = Section::create($request->only('name', 'course_id'));

        return response()->json([
            'message' => 'Section berhasil dibuat',
            'data' => $section
        ], 201);
    }

    // GET - Show section by ID
    public function show($id)
    {
        $section = Section::with('course', 'videos', 'quiz')->findOrFail($id);
        return response()->json($section);
    }

    // PUT/PATCH - Update section
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $request->validate([
            'name'      => 'sometimes|required|string|max:255',
            'course_id' => 'sometimes|required|exists:courses,id',
        ]);

        $section->update($request->only('name', 'course_id'));

        return response()->json([
            'message' => 'Section berhasil diperbarui',
            'data' => $section
        ]);
    }

    // DELETE - Delete section
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return response()->json(['message' => 'Section berhasil dihapus']);
    }
}
