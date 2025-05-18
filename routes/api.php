<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\CourseAccessController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuizQuestionController;
use App\Http\Controllers\QuizAnswerController;
use App\Http\Controllers\QuizResultController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoAccessController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
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

Route::options('/{any}', function (Request $request) {
    return response()->json([], 204);
})->where('any', '.*');
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/mentors', [UserController::class, 'fetchMentors']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//fitur search course 
Route::get('/courses/search', [CourseController::class, 'search']);
Route::get('/courses/mentor/{id}', [CourseController::class, 'filterByUser']);
Route::get('/videos', [VideoController::class, 'index']);  

//Routes Login Register 3 Role
Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);

    Route::get('/profile', [UserController::class, 'show']);
    Route::put('/edit/profile', [UserController::class, 'update']);

    Route::post('/upload-image/{id}', [UserController::class, 'uploadImage']);


    //Routes CRUD Tabel Category
    // Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    Route::post('/courses/{id}/upload-image', [CourseController::class, 'uploadImage']);
    Route::get('/courses/{id}/reviews', [ReviewController::class, 'filterByCourse']);
    Route::get('/categories/{id}/courses', [CourseController::class, 'filterByCategory']);
 


    // Route::get('/course-accesses', [CourseAccessController::class, 'index']);
    Route::post('/course-accesses', [CourseAccessController::class, 'store']);
    Route::get('/course-accesses/{id}', [CourseAccessController::class, 'show']);
    Route::put('/course-accesses/{id}', [CourseAccessController::class, 'update']);
    Route::delete('/course-accesses/{id}', [CourseAccessController::class, 'destroy']);
    Route::get('/course-access', [CourseAccessController::class, 'index']);
    Route::get('/course-access/enrolled/check-enrollment', [CourseAccessController::class, 'isEnrolled']);
    // Route::post('/course-access/enroll', [CourseAccessController::class, 'enroll']);
    // Route::put('/course-access/{id}/status', [CourseAccessController::class, 'updateStatus']);
    // Route::get('/course-access/{id}', [CourseAccessController::class, 'show']);


    Route::get('/sections', [SectionController::class, 'index']);
    Route::post('/courses/{course}/sections', [SectionController::class, 'store']);
    Route::get('/sections/{id}', [SectionController::class, 'show']);
    Route::put('/sections/{id}', [SectionController::class, 'update']);
    Route::delete('/sections/{id}', [SectionController::class, 'destroy']);

    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::post('/quizzes', [QuizController::class, 'store']);
    Route::get('/quizzes/{id}', [QuizController::class, 'show']);
    Route::put('/quizzes/{id}', [QuizController::class, 'update']);
    Route::delete('/quizzes/{id}', [QuizController::class, 'destroy']);
    Route::post('/quizzes/get-or-create', [QuizController::class, 'getOrCreateQuiz']);
    Route::post('/quizzes/{quizId}/questions', [QuizController::class, 'storeQuestionWithAnswers']);
    Route::get('/quiz-questions', [QuizQuestionController::class, 'index']);
    Route::post('/quiz-questions', [QuizQuestionController::class, 'store']);
    Route::get('/quiz-questions/{id}', [QuizQuestionController::class, 'show']);
    Route::put('/quiz-questions/{id}', [QuizQuestionController::class, 'update']);
    Route::delete('/quiz-questions/{id}', [QuizQuestionController::class, 'destroy']);
    Route::put('/quizzes/{quizId}/questions/{questionId}', [QuizController::class, 'editQuestionWithAnswers']);
    Route::get('/quizzes/{id}/details', [QuizController::class, 'showQuizWithDetails']);
    Route::post('/quiz-results', [QuizResultController::class, 'store']);
    Route::get('/quiz-results', [QuizResultController::class, 'index']);
    Route::get('/quiz-results/{quizId}', [QuizResultController::class, 'showByQuiz']);
    Route::get('/quiz-answers', [QuizAnswerController::class, 'index']);
    Route::post('/quiz-answers', [QuizAnswerController::class, 'store']);
    Route::get('/quiz-answers/{id}', [QuizAnswerController::class, 'show']);
    Route::put('/quiz-answers/{id}', [QuizAnswerController::class, 'update']);
    Route::delete('/quiz-answers/{id}', [QuizAnswerController::class, 'destroy']);
    Route::get('/quiz-id/{sectionId}', [QuizController::class, 'getQuizIdBySectionId']);
   

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

    Route::post('/chat-rooms', [ChatRoomController::class, 'createRoom']);
    Route::get('/chat-rooms', [ChatRoomController::class, 'myRooms']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::get('/chats/{chatRoomId}', [ChatController::class, 'index']);
    
    // tambah route lain yang butuh login di sini
    // contoh: Route::get('/profile', fn (Request $request) => $request->user());
});

