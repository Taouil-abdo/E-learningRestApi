<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    public function all()
    {
        try {
            $courses = Course::with(['category', 'tags', 'teacher'])
                ->select('courses.*')
                ->orderBy('id', 'DESC')->get();
            if ($courses->isEmpty()) {
                return response()->json(["message" => "No courses available!"]);
            }

            return response()->json(['courses' => new CourseCollection($courses)]);
        } catch (Exception $e) {
            return $this->error('', 500, 'Failed to fetch courses: ' . $e->getMessage());
        }    }

    public function find($id)
    {
        try {
            $course = Course::with(['category', 'tags', 'teacher'])->find($id);
            if (!$course) {
                return response()->json(["message" => "Course not found!"]);
            }
            return new CourseResource($course);
        } catch (Exception $e) {
            return $this->error('', 500, 'Failed to fetch course');
        }
    }

    public function create(array $data)
    {
        try {
            $course = Course::create($data);

            if (isset($data['tags'])) {
                $course->tags()->attach($data['tags']);
            }

            return $this->success(["course" => $course, "message" => "Course added successfully"]);
        } catch (Exception $e) {
            return $this->error('', 500, 'Failed to create course');
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

            return $this->success(["course" => $course, "message" => "Course updated successfully"]);
        } catch (Exception $e) {
            return $this->error('', 500, 'Failed to update course');
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
            return $this->success(["message" => "Course deleted successfully"]);
        } catch (Exception $e) {
            return $this->error('', 500, 'Failed to delete course');
        }
    }
}

