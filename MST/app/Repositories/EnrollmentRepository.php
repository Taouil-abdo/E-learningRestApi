<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function enrollStudentInCourse(int $studentId, int $courseId)
    {
        $student = Student::find($studentId);
        $course = Course::find($courseId);
        
        if (!$student || !$course) {
            return response()->json(['message' => 'Student or Course not found'], 404);
        }

        $enrollment = Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
        ]);

        return $enrollment;
    }

    public function getEnrollmentsForStudent(int $studentId)
    {
        return Enrollment::with('course')->where('student_id', $studentId)->get();
    }
}
