<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    // GET: Ambil semua video (bisa pakai filter section_id)
    public function index(Request $request)
    {
        $videos = Video::with('section');

        if ($request->has('section_id')) {
            $videos->where('section_id', $request->section_id);
        }

        return response()->json($videos->get());
    }
 
  public function store(Request $request)
  {
      $request->validate([
          'title' => 'required|string',
          'url' => 'required|url',
          'duration' => 'required|string',         
          'section_id' => 'required|exists:sections,id',
      ]);
  
      $video = Video::create([
          'title' => $request->title,
          'url' => $request->url,
          'section_id' => $request->section_id, 
          'duration' => $request->duration,
      ]);
  
      return response()->json([
          'message' => 'Video berhasil ditambahkan',
          'data' => $video,
      ], 201);
  }

  
    // GET: Ambil detail video
    public function show($id)
    {
        $video = Video::with('section')->findOrFail($id);
        return response()->json($video);
    }

    // PUT: Update video
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'url' => 'required|url',
            'section_id' => 'required|exists:sections,id',
            'duration' => 'required|string'
        ]);

        $video->update($request->only('title', 'url', 'section_id', 'duration'));

        return response()->json([
            'message' => 'Video berhasil diperbarui',
            'data' => $video
        ]);
    }

    // DELETE: Hapus video
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return response()->json(['message' => 'Video berhasil dihapus']);
    }
}
