<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/courses",
     *     tags={"Course"},
     *     summary="List all courses",
     *     @OA\Response(response=200, description="List of courses")
     * )
     */
    public function index()
    {
        return response()->json($this->courseRepository->all());
    }

    /**
     * @OA\Post(
     *     path="/api/courses",
     *     tags={"Course"},
     *     summary="Create a new course",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test Course"),
     *             @OA\Property(property="description", type="string", example="This is a test course."),
     *             @OA\Property(property="duration", type="integer", example=50),
     *             @OA\Property(property="difficulty_level", type="string", example="Intermediate"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="sub_category_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="open")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Course created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'difficulty_level' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'status' => 'required|string',
        ]);
        return response()->json($this->courseRepository->create($validated), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/courses/{id}",
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
        return response()->json($this->courseRepository->find($id));
    }

    /**
     * @OA\Put(
     *     path="/api/courses/{id}",
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
     *             @OA\Property(property="name", type="string", example="Updated Course"),
     *             @OA\Property(property="description", type="string", example="This is an updated course."),
     *             @OA\Property(property="duration", type="integer", example=60),
     *             @OA\Property(property="difficulty_level", type="string", example="Advanced"),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(property="sub_category_id", type="integer", example=1),
     *             @OA\Property(property="status", type="string", example="in progress")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Course updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer',
            'difficulty_level' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'status' => 'required|string',
        ]);
        return response()->json($this->courseRepository->update($id, $validated));
    }

    /**
     * @OA\Delete(
     *     path="/api/courses/{id}",
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
