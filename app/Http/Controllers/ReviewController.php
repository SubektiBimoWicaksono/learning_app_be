<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = $request->has('course_id')
            ? Review::where('course_id', $request->course_id)->with('course')->get()
            : Review::with('course')->get();

        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reviews' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'course_id' => 'required|exists:courses,id',
        ]);

        $review = Review::create([
            'reviews' => $request->reviews,
            'rating' => $request->rating,
            'datetime' => now(),
            'course_id' => $request->course_id,
        ]);

        return response()->json(['message' => 'Review berhasil ditambahkan', 'data' => $review]);
    }

    public function show($id)
    {
        $review = Review::findOrFail($id);
        return response()->json($review);
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'reviews' => 'string|nullable',
            'rating' => 'numeric|min:1|max:5|nullable',
        ]);

        $review->update($request->only(['reviews', 'rating']));

        return response()->json(['message' => 'Review berhasil diupdate', 'data' => $review]);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}
