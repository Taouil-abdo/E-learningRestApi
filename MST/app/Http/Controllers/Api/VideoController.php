<?php
namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::all();
        return response()->json($videos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'video' => 'required',
        ]);

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $filename = time() . '_' . str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $video_path = $file->storeAs('public/videos', $filename);

            $video = new Video();
            $video->course_id = $request->course_id;
            $video->video = $filename;

            if ($video->save()) {
                return response()->json(['status' => true, 'message' => "Video uploaded successfully"]);
            } else {
                return response()->json(['status' => false, 'message' => "Error: Video not uploaded successfully"]);
            }
        }

        return response()->json(['status' => false, 'message' => "No video file provided"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $video = Video::findOrFail($id);

        $request->validate([
            'course_id' => 'exists:courses,id',
            'video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
        ]);

        if ($request->hasFile('video')) {
            // Delete the old video file if it exists
            if ($video->video) {
                Storage::delete('public/videos/' . $video->video);
            }

            $file = $request->file('video');
            $filename = time() . '_' . str_replace(' ', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $video_path = $file->storeAs('public/videos', $filename);
            $video->video = $filename;
        }

        if ($request->has('course_id')) {
            $video->course_id = $request->course_id;
        }

        if ($video->save()) {
            return response()->json(['status' => true, 'message' => "Video updated successfully"]);
        } else {
            return response()->json(['status' => false, 'message' => "Error: Video not updated successfully"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $video = Video::findOrFail($id);

        // Delete the video file if it exists
        if ($video->video) {
            Storage::delete('public/videos/' . $video->video);
        }

        if ($video->delete()) {
            return response()->json(['status' => true, 'message' => "Video deleted successfully"]);
        } else {
            return response()->json(['status' => false, 'message' => "Error: Video not deleted successfully"]);
        }
    }
}


