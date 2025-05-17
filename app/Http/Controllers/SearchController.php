<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = $request->keyword;

        // Search Course by name
        $courses = Course::where('name', 'LIKE', '%' . $keyword . '%')
            ->with('user') // Optional: supaya data user pengajar ikut muncul
            ->with('category') // Optional: supaya data user pengajar ikut muncul
            ->get();

        // Search Mentor by name (role = mentor)
        $mentors = User::where('role', 'mentor')
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->get();

        return response()->json([
            'courses' => $courses,
            'mentors' => $mentors,
            
        ]);
    }
}
