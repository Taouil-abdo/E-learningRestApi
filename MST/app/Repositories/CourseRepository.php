<?php
namespace App\Repositories;

use App\Models\Course;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use Exception;

class CourseRepository implements CourseRepositoryInterface
{
    public function all()
    {
        try {
            $courses = Course::with(['category', 'tags', 'teacher'])
                ->orderBy('id', 'DESC')->get();
            if ($courses->isEmpty()) {
                return response()->json(["message" => "No courses available!"]);
            }
            return new CourseCollection($courses);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch courses: ' . $e->getMessage()], 500);
        }
    }

    public function find($id)
    {
        try {
            $course = Course::with(['category', 'tags', 'teacher'])->find($id);
            if (!$course) {
                return response()->json(["message" => "Course not found!"]);
            }
            return new CourseResource($course);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch course'], 500);
        }
    }

    public function create(array $data)
    {
        try {
            $course = Course::create($data);
            if (isset($data['tags'])) {
                $course->tags()->attach($data['tags']);
            }
            return response()->json(["course" => new CourseResource($course), "message" => "Course added successfully"], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create course: ' . $e->getMessage()], 500);
        }
    }

    public function update($id, array $data)
    {
        try {
            $course = Course::find($id);
            if (!$course) {
                return response()->json(["message" => "Course not found!"]);
            }
            $course->update($data);
            if (isset($data['tags'])) {
                $course->tags()->sync($data['tags']);
            }
            return response()->json(["course" => new CourseResource($course), "message" => "Course updated successfully"]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update course: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $course = Course::find($id);
            if (!$course) {
                return response()->json(["message" => "Course not found!"]);
            }
            $course->delete();
            return response()->json(["message" => "Course deleted successfully"], 204);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete course: ' . $e->getMessage()], 500);
        }
    }
}
