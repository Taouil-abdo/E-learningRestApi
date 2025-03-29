<?php

namespace App\Repositories;

use App\Models\Video;
use Illuminate\Support\Facades\Log;
use App\Repositories\VideoRepositoryInterface;

class VideoRepository implements VideoRepositoryInterface
{
    public function create(array $data)
    {
        Log::info('Creating video with data:', ['data' => $data]);

        try {
            $video = Video::create($data);

            // Log success after creation
            Log::info('Video created successfully:', ['video' => $video]);

            return $video;
        } catch (\Exception $e) {
            Log::error('Error creating video in repository:', ['error' => $e->getMessage()]);
            throw $e;  // Re-throw the exception so it can be caught in the controller
        }
    }

    public function getAllByCourseId($courseId)
    {
        Log::info('Fetching all videos for course ID:', ['course_id' => $courseId]);

        try {
            $videos = Video::where('course_id', $courseId)->get();

            // Log success after fetching
            Log::info('Videos fetched successfully:', ['videos' => $videos]);

            return $videos;
        } catch (\Exception $e) {
            Log::error('Error fetching videos in repository:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getById($id)
    {
        Log::info('Fetching video by ID:', ['video_id' => $id]);

        try {
            $video = Video::findOrFail($id);

            // Log success after fetching
            Log::info('Video fetched successfully:', ['video' => $video]);

            return $video;
        } catch (\Exception $e) {
            Log::error('Error fetching video in repository:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        Log::info('Updating video with ID:', ['video_id' => $id, 'data' => $data]);

        try {
            $video = Video::findOrFail($id);
            $video->update($data);

            // Log success after updating
            Log::info('Video updated successfully:', ['video' => $video]);

            return $video;
        } catch (\Exception $e) {
            Log::error('Error updating video in repository:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function delete($id)
    {
        Log::info('Deleting video with ID:', ['video_id' => $id]);

        try {
            $video = Video::findOrFail($id);
            $video->delete();

            // Log success after deleting
            Log::info('Video deleted successfully:', ['video_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Error deleting video in repository:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
