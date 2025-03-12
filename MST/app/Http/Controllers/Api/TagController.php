<?php
namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;
use App\Repositories\TagRepository;
use App\Http\Controllers\Controller;


class TagController extends Controller
{

    protected $tagRepository;
     
    public function __construct(TagRepository $tagRepository){
        $this->tagRepository = $tagRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/tags",
     *     tags={"tag"},
     *     summary="List all tags",
     *     @OA\Response(response=200, description="List of tags")
     * )
     */
    public function index()
    {
        return response()->json($this->tagRepository->all());
    }

    /**
     * @OA\Post(
     *     path="/api/tags",
     *     tags={"tag"},
     *     summary="Create a new tag",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test tag"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="tag created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        return response()->json($this->tagRepository->create($validated), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/tags/{id}",
     *     tags={"tag"},
     *     summary="Show a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="tag details")
     * )
     */
    public function show($id)
    {
        return response()->json($this->tagRepository->find($id));
    }

    /**
     * @OA\Put(
     *     path="/api/tags/{id}",
     *     tags={"tag"},
     *     summary="Update a tag",
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
     *             @OA\Property(property="name", type="string", example="Updated tag"),
     *         )
     *     ),
     *     @OA\Response(response=200, description="tag updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        return response()->json($this->tagRepository->update($id, $validated));
    }

    /**
     * @OA\Delete(
     *     path="/api/tags/{id}",
     *     tags={"tag"},
     *     summary="Delete a tag",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="tag deleted")
     * )
     */
    public function destroy($id)
    {
        return response()->json($this->tagRepository->delete($id));
    }

    
}
