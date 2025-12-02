<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    
  public function index($courseId)
{
    $videos = Video::where('course_id', $courseId)
                   ->with('course') 
                   ->orderBy('video_order')
                   ->get();

    // استخراج publisher_id من أول فيديو (لأن كل الفيديوهات لها نفس course_id)
    $publisherId = optional($videos->first()->course)->publisher_id;

    return response()->json([
        'publisher_id' => $publisherId,
        'videos' => $videos,
    ]);
}


    /**
     * Store a newly created video in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'video_order' => 'nullable|integer',
            'duration' => 'required|date_format:H:i:s'
        ]);

        $video = Video::create($validated);

        return response()->json($video, 201);
    }

    /**
     * Display the specified video.
     */
   public function show($id)
{
    $video = Video::with('course')->findOrFail($id);

    return response()->json([
        'id' => $video->id,
        'title' => $video->title,
        'video_order' => $video->video_order,
        'course_id' => $video->course_id,
        'publisher_id' => optional($video->course)->publisher_id,
        // أضف أي حقول أخرى تريدها من الفيديو
    ]);
}


    /**
     * Update the specified video in storage.
     */
    public function update(Request $request, $id)
    {
        $video = Video::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'sometimes|url',
            'video_order' => 'nullable|integer',
            'duration' => 'sometimes|date_format:H:i:s'
        ]);

        $video->update($validated);

        return response()->json($video);
    }

    /**
     * Remove the specified video from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        return response()->json($video, 204);
    }
}