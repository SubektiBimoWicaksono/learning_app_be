<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    // GET: List semua quiz
    public function index(Request $request)
    {
        $quizzes = Quiz::with('section', 'questions');

        if ($request->has('section_id')) {
            $quizzes->where('section_id', $request->section_id);
        }

        return response()->json($quizzes->get());
    }
    public function storeQuestionWithAnswers(Request $request, $quizId)
    {
        $request->validate([
            'question' => 'required|string',
            'answers' => 'required|array',
            'answers.*.answer' => 'required|string',
            'answers.*.status' => 'required|boolean',
        ]);
    
        // Pastikan quiz ada
        $quiz = Quiz::findOrFail($quizId);
    
        // Simpan pertanyaan
        $quizQuestion = $quiz->questions()->create([
            'question' => $request->question,
        ]);
    
        // Simpan jawaban
        foreach ($request->answers as $answer) {
            $quizQuestion->answers()->create($answer);
        }
    
        return response()->json([
            'message' => 'Quiz question dan answers berhasil disimpan',
            'data' => [
                'question' => $quizQuestion,
                'answers' => $quizQuestion->answers,
            ],
        ], 201);
    }


public function showQuizWithDetails($id)
{
    $quiz = Quiz::with(['questions.answers'])->findOrFail($id);

    return response()->json([
        'message' => 'Detail quiz dengan questions dan answers',
        'data' => $quiz,
    ]);
}
    // POST: Buat quiz baru
    public function store(Request $request)
    {

        $user = auth()->user();

        // Cek apakah role-nya mentor
        if ($user->role !== 'mentor') {
            return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat Section'], 403);
        }
        
        $request->validate([
            'section_id' => 'required|exists:sections,id|unique:quizzes,section_id'
        ]);

        $quiz = Quiz::create([
            'section_id' => $request->section_id
        ]);

        return response()->json([
            'message' => 'Quiz berhasil dibuat',
            'data' => $quiz
        ], 201);
    }

    // GET: Detail quiz
    public function show($id)
    {
        $quiz = Quiz::with('section', 'questions')->findOrFail($id);
        return response()->json($quiz);
    }

    // PUT: Update quiz
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        $request->validate([
            'section_id' => 'required|exists:sections,id|unique:quizzes,section_id,' . $id
        ]);

        $quiz->update([
            'section_id' => $request->section_id
        ]);

        return response()->json([
            'message' => 'Quiz berhasil diperbarui',
            'data' => $quiz
        ]);
    }

    // DELETE: Hapus quiz
    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return response()->json(['message' => 'Quiz berhasil dihapus']);
    }
}
