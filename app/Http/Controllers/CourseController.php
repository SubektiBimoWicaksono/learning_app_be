<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Tampilkan semua course
    public function index()
    {
        return response()->json(Course::with(['user', 'category'])->get());
    }

    // Tambah course baru
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah role-nya mentor
        if ($user->role !== 'mentor') {
            return response()->json(['message' => 'Hanya user dengan role mentor yang bisa membuat category'], 403);
        }

        // Validasi data kecuali user_id
        $request->validate([
            'name'              => 'required|string|max:255',
            'desc'              => 'nullable|string',
            'media_full_access' => 'nullable|boolean',
            'level'             => 'nullable|string',
            'audio_book'        => 'nullable|boolean',
            'lifetime_access'   => 'nullable|boolean',
            'certificate'       => 'nullable|boolean',
            'price'             => 'nullable|string',
            'category_id'       => 'nullable|exists:categories,id'
        ]);

        // Tambah course dengan user_id dari session login
        $course = Course::create([
            'name'              => $request->name,
            'desc'              => $request->desc,
            'user_id'           => $user->id,
            'media_full_access' => $request->media_full_access,
            'level'             => $request->level,
            'audio_book'        => $request->audio_book,
            'lifetime_access'   => $request->lifetime_access,
            'certificate'       => $request->certificate,
            'price'             => $request->price,
            'category_id'       => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Course berhasil dibuat oleh mentor',
            'data' => $course
        ], 201);
    }

    public function getAverageRating($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        // Menggunakan accessor yang sudah dibuat di model
        return response()->json([
            'course_id' => $id,
            'rating' => $course->average_rating, // Hasil dari getAverageRatingAttribute()
           // Hasil dari getTotalReviewsAttribute()
        ]);
    }

    // Tampilkan satu course
    public function show($id)
    {
        $course = Course::with('user')->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        return response()->json($course);
    }

    // Update course
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        $course->update($request->all());

        return response()->json([
            'message' => 'Course berhasil diperbarui',
            'data' => $course
        ]);
    }

    // Hapus course
    public function destroy($id)
    {
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        $course->delete();

        return response()->json(['message' => 'Course berhasil dihapus']);
    }

    // Filter course berdasarkan category_id
        public function filterByCategory($id)
    {
        $courses = Course::with(['user', 'category'])
            ->where('category_id', $id)
            ->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'Course tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Daftar course berdasarkan kategori',
            'data' => $courses
        ]);
    }

    // Filter course berdasarkan user_id
    public function filterByUser($id)
    {
        $courses = Course::with(['user', 'category'])
            ->where('user_id', $id)
            ->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'Course tidak ditemukan untuk user ini'], 404);
        }

        return response()->json([
            'message' => 'Daftar course berdasarkan user',
            'data' => $courses
        ]);
    }

    // Cari course berdasarkan keyword di kolom 'name'
    public function search(Request $request)
    {
        $keyword = $request->query('keyword');

        if (!$keyword) {
            return response()->json(['message' => 'Keyword pencarian tidak boleh kosong'], 400);
        }

        $courses = Course::with(['user', 'category'])
            ->where('name', 'like', '%' . $keyword . '%')
            ->get();

        if ($courses->isEmpty()) {
            return response()->json(['message' => 'Course tidak ditemukan dengan keyword tersebut'], 404);
        }

        return response()->json([
            'message' => 'Hasil pencarian course',
            'data' => $courses
        ]);
    }

// Upload gambar untuk course
public function uploadImage(Request $request, $id)
{
    $course = Course::find($id);

    if (!$course) {
        return response()->json(['message' => 'Course tidak ditemukan'], 404);
    }

    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // maksimal 2MB
    ]);

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/courses'), $imageName);

        // update course image path
        $course->update([
            'image' => 'uploads/courses/' . $imageName
        ]);

        return response()->json([
            'message' => 'Gambar berhasil diupload dan course diperbarui',
            'data' => $course
        ]);
    }

    return response()->json(['message' => 'Gagal upload gambar'], 400);
}

    

}
