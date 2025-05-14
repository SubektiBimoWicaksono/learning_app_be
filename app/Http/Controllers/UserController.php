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
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'string|max:255|nullable',
            'dob' => 'date|nullable',
            'email' => 'email|max:255|nullable|unique:users,email,' . $user->id,
            'no_telp' => 'string|max:20|nullable',
            'gender' => 'in:male,female,other|nullable',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('dob')) $user->dob = $request->dob;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('no_telp')) $user->no_telp = $request->no_telp;
        if ($request->has('gender')) $user->gender = $request->gender;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }

    public function uploadImage(Request $request, $id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
    
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif', // maksimal 2MB
        ]);
    
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/profile'), $imageName);
    
            // update course image path
            $user->update([
                'photo' => 'uploads/profile/' . $imageName
            ]);
    
            return response()->json([
                'message' => 'Gambar berhasil diupload dan cuserdiperbarui',
                'data' => $user
            ]);
        }
    
        return response()->json(['message' => 'Gagal upload gambar'], 400);
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

    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'string|max:255|nullable',
            'dob' => 'date|nullable',
            'email' => 'email|max:255|nullable|unique:users,email,' . $user->id,
            'no_telp' => 'string|max:20|nullable',
            'gender' => 'in:male,female,other|nullable',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('dob')) $user->dob = $request->dob;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('no_telp')) $user->no_telp = $request->no_telp;
        if ($request->has('gender')) $user->gender = $request->gender;

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user,
        ]);
    }
}
