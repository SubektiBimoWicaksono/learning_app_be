<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseAccess;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Fetch all mentor users
     */
    public function fetchMentors()
    {
        $mentors = User::where('role', 'mentor')->get();

        return response()->json([
            'message' => 'Daftar mentor',
            'data' => $mentors
        ]);
    }

    /**
     * Fetch all student users
     */
    public function fetchStudents()
    {
        $students = User::where('role', 'student')->get();

        return response()->json([
            'message' => 'Daftar student',
            'data' => $students
        ]);
    }

    /**
     * Fetch students by course access (ongoing, completed)
     * @param $course_id
     * @param $status
     */
    public function fetchStudentsByCourseAccess($course_id, $status)
    {
        $students = CourseAccess::where('course_id', $course_id)
            ->where('access_status', $status)
            ->with('user')
            ->get();

        return response()->json([
            'message' => 'Daftar students berdasarkan akses course',
            'data' => $students
        ]);
    }

    /**
     * Fetch user by course mentor
     * @param $mentor_id
     */
    public function fetchUsersByCourseMentor($mentor_id)
    {
        $courses = Course::where('user_id', $mentor_id)->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'Mentor tidak memiliki kursus'], 404);
        }

        $users = [];
        foreach ($courses as $course) {
            $courseUsers = $course->courseAccesses()->with('user')->get();
            foreach ($courseUsers as $courseUser) {
                $users[] = $courseUser->user;
            }
        }

        return response()->json([
            'message' => 'Daftar user berdasarkan mentor',
            'data' => $users
        ]);
    }
}
