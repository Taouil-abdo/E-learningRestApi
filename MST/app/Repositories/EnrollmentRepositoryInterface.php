<?php

namespace App\Repositories;


interface EnrollmentRepositoryInterface
{
    public function enrollStudentInCourse(int $studentId, int $courseId);
    public function getEnrollmentsForStudent(int $studentId);
}
