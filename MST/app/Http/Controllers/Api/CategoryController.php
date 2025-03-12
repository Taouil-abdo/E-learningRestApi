<?php

namespace App\Http\Controllers\Api;

use OpenApi\Annotations as OA;
// use Illuminate\Http\Client\Request;
// use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
     protected $categoryRepository;

     public function __construct(CategoryRepository $categoryRepository)
     {
         $this->categoryRepository = $categoryRepository;
     }
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="List all categories",
     *     @OA\Response(response=200, description="List of categories")
     * )
     */
    public function index()
    {
        return response()->json($this->categoryRepository->all());
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Category"},
     *     summary="Create a new category",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="Test Category"),
     *             @OA\Property(property="description", type="string", example="This is a test category.")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Category created")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'parent_id' =>'nullable'

        ]);
        return response()->json($this->categoryRepository->create($validated), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Show a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category details")
     * )
     */
    public function show($id)
    {
        return response()->json($this->categoryRepository->find($id));
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Update a category",
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
     *             @OA\Property(property="name", type="string", example="Updated Category"),
     *             @OA\Property(property="description", type="string", example="This is an updated category.")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Category updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' =>'nullable'
        ]);
        return response()->json($this->categoryRepository->update($id, $validated));
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     tags={"Category"},
     *     summary="Delete a category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Category deleted")
     * )
     */
    public function destroy($id)
    {
        return response()->json($this->categoryRepository->delete($id));
    }

    
}
