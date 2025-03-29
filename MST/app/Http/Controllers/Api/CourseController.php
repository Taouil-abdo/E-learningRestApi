<?php
namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CourseResource;
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
        try {
            return $this->courseRepository->all();
        } catch (Exception $e) {
            Log::error('Error fetching courses', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch courses'], 500);
        }
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
    // dd($request->all());
    $data = $request->validated();

    // $data['teacher_id'] = Auth::id(); 
    Log::info('Request Data:', $data); 
    try {
        $course = $this->courseRepository->addNewCourse($data);
        Log::info('Course Created:', $course->toArray()); 

        return response()->json(['success' => true,'message' => "Course created successfully",
            'data' => new CourseResource($course)], 201);
    } catch (\Exception $e) {
        Log::error("Error creating course: " . $e->getMessage());
        return response()->json(['success' => false,'message' => "Course creation failed"], 500);
    }
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
        try {
            return $this->courseRepository->find($id);
        } catch (Exception $e) {
            Log::error('Error fetching course details', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to fetch course details'], 500);
        }
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
        try {
            return response()->json($this->courseRepository->update($id, $request->validated()));
        } catch (Exception $e) {
            Log::error('Error updating course', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to update course'], 500);
        }
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
        try {
            $this->courseRepository->delete($id);
            return response()->json(null, 204);
        } catch (Exception $e) {
            Log::error('Error deleting course', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete course'], 500);
        }
    }
}
