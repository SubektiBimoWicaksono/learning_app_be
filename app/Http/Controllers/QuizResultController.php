<?php
namespace App\Http\Controllers;

use App\Models\QuizResult;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array', // Jawaban dari pengguna
            'answers.*.question_id' => 'required|exists:quiz_questions,id',
            'answers.*.answer_id' => 'required|exists:quiz_answers,id',
        ]);

        $quizId = $validated['quiz_id'];
        $answers = $validated['answers'];

        $correctAnswers = 0;
        $totalQuestions = QuizQuestion::where('quiz_id', $quizId)->count();

        foreach ($answers as $answer) {
            $questionId = $answer['question_id'];
            $answerId = $answer['answer_id'];

            // Periksa apakah jawaban benar
            $isCorrect = \DB::table('quiz_answers')
                ->where('id', $answerId)
                ->where('quiz_question_id', $questionId)
                ->where('status', 1) // Status 1 berarti jawaban benar
                ->exists();

            if ($isCorrect) {
                $correctAnswers++;
            }
        }

        // Hitung skor
        $score = ($correctAnswers / $totalQuestions) * 100;

        // Simpan hasil quiz
        $result = QuizResult::create([
            'quiz_id' => $quizId,
            'user_id' => $request->user()->id,
            'score' => round($score),
        ]);

        return response()->json([
            'message' => 'Quiz result saved successfully',
            'data' => $result,
        ], 201);
    }

    public function index(Request $request)
    {
        // Ambil semua hasil quiz berdasarkan user yang sedang login
        $results = QuizResult::where('user_id', $request->user()->id)
            ->with('quiz') // Opsional: Jika Anda ingin memuat data quiz terkait
            ->get();

        return response()->json(['data' => $results], 200);
    }

    public function showByQuiz(Request $request, $quizId)
    {
        $user = auth()->user();

        // Ambil hasil quiz berdasarkan quiz_id dan user_id
        $result = QuizResult::where('quiz_id', $quizId)
            ->where('user_id', $user->id)
            ->with('quiz') // Opsional: Memuat data quiz terkait
            ->first();

        if (!$result) {
            return response()->json(['message' => 'Quiz result not found'], 404);
        }

        return response()->json(['data' => $result], 200);
    }
}