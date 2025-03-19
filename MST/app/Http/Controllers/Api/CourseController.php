<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
// use App\Repositories\CourseRepository;
use App\Repositories\CourseRepositoryInterface;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courses",
     *     tags={"Course"},
     *     summary="List all courses",
     *     @OA\Response(response=200, description="List of courses")
     * )
     */
    public function index()
    {
        return $this->courseRepository->all();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/courses",
     *     tags={"Course"},
     *     summary="Create a new course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Test Course"),
     *             @OA\Property(property="description", type="string", example="This is a test course."),
     *             @OA\Property(property="duration", type="integer", example=50),
     *             @OA\Property(property="difficulty_level", type="string", example="Intermediate"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="open"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer"), example={1, 2})
     *         )
     *     ),
     *     @OA\Response(response=201, description="Course created")
     * )
     */
    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $data['teacher_id'] = auth()->id();
        return response()->json($this->courseRepository->create($data), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courses/{id}",
     *     tags={"Course"},
     *     summary="Show a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Course details")
     * )
     */
    public function show($id)
    {
        return $this->courseRepository->find($id);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/courses/{id}",
     *     tags={"Course"},
     *     summary="Update a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="title", type="string", example="Updated Course"),
     *             @OA\Property(property="description", type="string", example="This is an updated course."),
     *             @OA\Property(property="duration", type="integer", example=60),
     *             @OA\Property(property="difficulty_level", type="string", example="Advanced"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="in progress"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="integer"), example={1, 2})
     *         )
     *     ),
     *     @OA\Response(response=200, description="Course updated")
     * )
     */
    public function update(CourseRequest $request, $id)
    {
        return response()->json($this->courseRepository->update($id, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/courses/{id}",
     *     tags={"Course"},
     *     summary="Delete a course",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Course deleted")
     * )
     */
    public function destroy($id)
    {
        $this->courseRepository->delete($id);
        return response()->json(null, 204);
    }
}
