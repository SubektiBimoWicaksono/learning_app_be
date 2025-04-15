<?php

namespace App\Http\Controllers;

use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizAnswerController extends Controller
{
    // GET: Tampilkan semua jawaban (optional filter by question_id)
    public function index(Request $request)
    {
        $answers = QuizAnswer::with('question');

        if ($request->has('quiz_question_id')) {
            $answers->where('quiz_question_id', $request->quiz_question_id);
        }

        return response()->json($answers->get());
    }

    // POST: Tambah jawaban baru
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah role-nya mentor
        if ($user->role !== 'mentor') {
            return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat Section'], 403);
        }
        
        $request->validate([
            'quiz_question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required|string',
            'status' => 'required|boolean' // true jika jawaban benar
        ]);

        $answer = QuizAnswer::create($request->only('quiz_question_id', 'answer', 'status'));

        return response()->json([
            'message' => 'Jawaban berhasil ditambahkan',
            'data' => $answer
        ], 201);
    }

    // GET: Tampilkan detail jawaban
    public function show($id)
    {
        $answer = QuizAnswer::with('question')->findOrFail($id);
        return response()->json($answer);
    }

    // PUT: Update jawaban
    public function update(Request $request, $id)
    {
        $answer = QuizAnswer::findOrFail($id);

        $request->validate([
            'quiz_question_id' => 'required|exists:quiz_questions,id',
            'answer' => 'required|string',
            'status' => 'required|boolean'
        ]);

        $answer->update($request->only('quiz_question_id', 'answer', 'status'));

        return response()->json([
            'message' => 'Jawaban berhasil diperbarui',
            'data' => $answer
        ]);
    }

    // DELETE: Hapus jawaban
    public function destroy($id)
    {
        $answer = QuizAnswer::findOrFail($id);
        $answer->delete();

        return response()->json(['message' => 'Jawaban berhasil dihapus']);
    }
}
