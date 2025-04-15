<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseAccessController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoAccessController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\AuthenticationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test', function () {
    return response([
        'message' => 'Api is working'
    ], 200);
});

//Routes Login Register 3 Role
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    //Routes CRUD Tabel Category
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

    Route::get('/course-accesses', [CourseAccessController::class, 'index']);
    Route::post('/course-accesses', [CourseAccessController::class, 'store']);
    Route::get('/course-accesses/{id}', [CourseAccessController::class, 'show']);
    Route::put('/course-accesses/{id}', [CourseAccessController::class, 'update']);
    Route::delete('/course-accesses/{id}', [CourseAccessController::class, 'destroy']);


    Route::get('/sections', [SectionController::class, 'index']);
    Route::post('/sections', [SectionController::class, 'store']);
    Route::get('/sections/{id}', [SectionController::class, 'show']);
    Route::put('/sections/{id}', [SectionController::class, 'update']);
    Route::delete('/sections/{id}', [SectionController::class, 'destroy']);

    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::put('/quizzes/{id}', [QuizController::class, 'update']);
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);

    Route::get('/quiz-questions', [QuizQuestionController::class, 'index']);
    Route::post('/quiz-questions', [QuizQuestionController::class, 'store']);
    Route::get('/quiz-questions/{id}', [QuizQuestionController::class, 'show']);
    Route::put('/quiz-questions/{id}', [QuizQuestionController::class, 'update']);
    Route::delete('/quiz-questions/{id}', [QuizQuestionController::class, 'destroy']);

    Route::get('/quiz-answers', [QuizAnswerController::class, 'index']);
    Route::post('/quiz-answers', [QuizAnswerController::class, 'store']);
    Route::get('/quiz-answers/{id}', [QuizAnswerController::class, 'show']);
    Route::put('/quiz-answers/{id}', [QuizAnswerController::class, 'update']);
    Route::delete('/quiz-answers/{id}', [QuizAnswerController::class, 'destroy']);

    Route::get('/videos', [VideoController::class, 'index']);
    Route::post('/videos', [VideoController::class, 'store']);
    Route::get('/videos/{id}', [VideoController::class, 'show']);
    Route::put('/videos/{id}', [VideoController::class, 'update']);
    Route::delete('/videos/{id}', [VideoController::class, 'destroy']);

    Route::get('/video-access', [VideoAccessController::class, 'index']);
    Route::post('/video-access', [VideoAccessController::class, 'store']);
    Route::get('/video-access/{course_id}', [VideoAccessController::class, 'show']);
    Route::delete('/video-access/{course_id}', [VideoAccessController::class, 'destroy']);

    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{id}', [ReviewController::class, 'show']);
    Route::put('/reviews/{id}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);


    // tambah route lain yang butuh login di sini
    // contoh: Route::get('/profile', fn (Request $request) => $request->user());
});