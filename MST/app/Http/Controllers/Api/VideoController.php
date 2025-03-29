<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Repositories\VideoRepositoryInterface;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    protected $videoRepository;

    public function __construct(VideoRepositoryInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function store(Request $request, $id)
    {
            // dd($request->all());
        Log::info('Store Video Request:', ['request' => $request->all(), 'course_id' => $id]);

        $data =$request->validate([
            'video' => 'required',
            'course_id' => 'required',
        ]);


        Log::info('Validation successful for store video.');

        try {
            $video = $this->videoRepository->create($data);
            // dd($video);

            Log::info('Video created successfully:', ['video' => $video]);

            return response()->json(['message' => 'Video added successfully', 'video' => $video], 201);
        } catch (\Exception $e) {
            Log::error('Error creating video:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error creating video'], 500);
        }
    }

    public function index($id)
    {
        Log::info('Fetch videos for course ID:', ['course_id' => $id]);

        try {
            $videos = $this->videoRepository->getAllByCourseId($id);
            Log::info('Videos fetched successfully:', ['videos' => $videos]);

            return response()->json($videos);
        } catch (\Exception $e) {
            Log::error('Error fetching videos:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching videos'], 500);
        }
    }

    public function show($id)
    {
        Log::info('Fetching video by ID:', ['video_id' => $id]);

        try {
            $video = $this->videoRepository->getById($id);

            Log::info('Video fetched successfully:', ['video' => $video]);

            return response()->json($video);
        } catch (\Exception $e) {
            Log::error('Error fetching video:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error fetching video'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Update video request:', ['video_id' => $id, 'request' => $request->all()]);

        $request->validate([
            'video' => 'url',
        ]);

        try {
            $video = $this->videoRepository->update($id, $request->only(['title', 'url']));
            Log::info('Video updated successfully:', ['video' => $video]);

            return response()->json(['message' => 'Video updated successfully', 'video' => $video]);
        } catch (\Exception $e) {
            Log::error('Error updating video:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error updating video'], 500);
        }
    }

    public function destroy($id)
    {
        Log::info('Delete video request:', ['video_id' => $id]);

        try {
            $this->videoRepository->delete($id);
            Log::info('Video deleted successfully:', ['video_id' => $id]);

            return response()->json(['message' => 'Video deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting video:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error deleting video'], 500);
        }
    }
    
}
