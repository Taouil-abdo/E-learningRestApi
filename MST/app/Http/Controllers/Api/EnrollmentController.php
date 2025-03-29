<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\EnrollmentRepositoryInterface;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use Illuminate\Http\Request;

/**
 * @OA\Info(title="Enrollment API", version="1.0")
 */
class EnrollmentController extends Controller
{
    protected $enrollmentRepo;

    public function __construct(EnrollmentRepositoryInterface $enrollmentRepo)
    {
        $this->enrollmentRepo = $enrollmentRepo;
    }

    /**
     * @OA\Post(
     *     path="/api/enrollments",
     *     summary="Enroll a student in a course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"student_id", "course_id"},
     *             @OA\Property(property="student_id", type="integer", example=1),
     *             @OA\Property(property="course_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student enrolled successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Enrollment")
     *     )
     * )
     */
    public function enrollStudent(Request $request)
    {
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $enrollment = $this->enrollmentRepo->enrollStudentInCourse($validated['student_id'], $validated['course_id']);
        
        return response()->json($enrollment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/enrollments/{studentId}",
     *     summary="Get all enrollments for a student",
     *     @OA\Parameter(
     *         name="studentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A list of enrollments",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Enrollment"))
     *     )
     * )
     */
    public function getStudentEnrollments(int $studentId)
    {
        $enrollments = $this->enrollmentRepo->getEnrollmentsForStudent($studentId);
        
        return response()->json($enrollments, 200);
    }
}
