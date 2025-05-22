<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoProgress;
use App\Models\CourseAccess;
use Illuminate\Http\Request;

class VideoProgressController extends Controller
{
    public function markVideoAsWatched(Request $request)
{
    $request->validate([
        'video_id' => 'required|exists:videos,id',
    ]);

    $userId = auth()->id();
    $videoId = $request->video_id;

    // Simpan ke video_progress (hindari duplikasi)
    VideoProgress::updateOrCreate(
        ['user_id' => $userId, 'video_id' => $videoId],
        ['watched_at' => now()]
    );

    // Ambil course_id dari video yang diklik
    $video = Video::with('section')->findOrFail($videoId);
    $courseId = $video->section->course_id;

    // Ambil semua video_id dari course tersebut
    $videoIdsInCourse = Video::whereIn('section_id', function ($query) use ($courseId) {
        $query->select('id')
            ->from('sections')
            ->where('course_id', $courseId);
    })->pluck('id')->toArray();

    // Ambil semua video_id yang sudah diklik user
    $watchedVideoIds = VideoProgress::where('user_id', $userId)
        ->whereIn('video_id', $videoIdsInCourse)
        ->pluck('video_id')
        ->toArray();

    // Cek apakah semua video sudah ditonton
    $allWatched = count(array_diff($videoIdsInCourse, $watchedVideoIds)) === 0;

    if ($allWatched) {
        CourseAccess::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->update(['access_status' => 'completed']);
    }

    return response()->json([
        'message' => 'Video marked as watched',
        'completed' => $allWatched,
    ]);
}

}
