<?php
namespace App\Repositories;

use Exception;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseCollection;

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

    // public function create(array $data)
    // {
    //     try {
    //         $course = Course::create($data);
    //         if (isset($data['tags'])) {
    //             $course->tags()->attach($data['tags']);
    //         }
    //         return response()->json(["course" => new CourseResource($course), "message" => "Course added successfully"], 201);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Failed to create course: ' . $e->getMessage()], 500);
    //     }
    // }

    public function addNewCourse(array $data): Course
{
    // dd($data);

    return DB::transaction(function () use ($data) {
        Log::info('Data before insertion:', $data); 

        $course = Course::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'duration' => $data['duration'],
            'difficulty_level' => $data['difficulty_level'],
            'status' => $data['status'],
            'category_id' => $data['category_id'],
            'teacher_id' => $data['teacher_id'],
        ]);

        Log::info('Course Inserted:', $course->toArray());

        if (!empty($data['tags'])) {
            Log::info('Attaching tags:', $data['tags']);
            $course->tags()->sync($data['tags']);
        }

        return $course->load(['category', 'tags']);
    });
}



    // public function getCoursesByTeacher($teacherId)
    // {
    //     try {
    //         $courses = Course::with(['category', 'tags'])
    //             ->where('teacher_id', $teacherId)
    //             ->orderBy('id', 'DESC')->get();
    //         if ($courses->isEmpty()) {
    //             return response()->json(["message" => "No courses available for this teacher!"]);
    //         }
    //         return new CourseCollection($courses);
    //     } catch (Exception $e) {
    //         return response()->json(['error' => 'Failed to fetch courses: ' . $e->getMessage()], 500);
    //     }
    // }

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
