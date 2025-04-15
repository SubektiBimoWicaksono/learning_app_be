<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    // GET: List semua pertanyaan (optional filter by quiz_id)
    public function index(Request $request)
    {
        $questions = QuizQuestion::with('quiz', 'answers');

        if ($request->has('quiz_id')) {
            $questions->where('quiz_id', $request->quiz_id);
        }

        return response()->json($questions->get());
    }

    // POST: Tambah pertanyaan baru
    public function store(Request $request)
    {

        $user = auth()->user();

        // Cek apakah role-nya mentor
        if ($user->role !== 'mentor') {
            return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat Section'], 403);
        }
        
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string'
        ]);

        $question = QuizQuestion::create($request->only('quiz_id', 'question'));

        return response()->json([
            'message' => 'Pertanyaan berhasil ditambahkan',
            'data' => $question
        ], 201);
    }

    // GET: Detail pertanyaan
    public function show($id)
    {
        $question = QuizQuestion::with('quiz', 'answers')->findOrFail($id);
        return response()->json($question);
    }

    // PUT: Update pertanyaan
    public function update(Request $request, $id)
    {
        $question = QuizQuestion::findOrFail($id);

        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question' => 'required|string'
        ]);

        $question->update($request->only('quiz_id', 'question'));

        return response()->json([
            'message' => 'Pertanyaan berhasil diperbarui',
            'data' => $question
        ]);
    }

    // DELETE: Hapus pertanyaan
    public function destroy($id)
    {
        $question = QuizQuestion::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Pertanyaan berhasil dihapus']);
    }
}
