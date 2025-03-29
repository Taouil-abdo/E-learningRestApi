<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Mentor;
use App\Models\Student;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function searchCourses(Request $request)
    {
        Log::info('Searching courses', ['query' => $request->all()]);
        $query = Course::query();

        if ($request->has('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $courses = $query->get();
        Log::info('Courses search result', ['count' => count($courses)]);

        return response()->json($courses);
    }

    public function searchMentors(Request $request)
    {
        Log::info('Searching mentors', ['query' => $request->search]);

        $query = Mentor::query();

        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('expertise', 'like', "%{$request->search}%");
        }

        $mentors = $query->get();
        Log::info('Mentors search result', ['count' => count($mentors)]);

        return response()->json($mentors);
    }

    public function filterStudentsByBadge(Request $request)
    {
        Log::info('Filtering students by badge', ['badge_id' => $request->badges]);

        if (!$request->has('badges')) {
            Log::error('Badge filter failed: Missing badge_id');
            return response()->json(['error' => 'Badge ID is required'], 400);
        }

        $students = Student::whereHas('badges', function ($query) use ($request) {
            $query->where('badge_id', $request->badges);
        })->get();

        Log::info('Filtered students', ['count' => count($students)]);

        return response()->json($students);
    }
}
