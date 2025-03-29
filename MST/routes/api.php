<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\EnrollmentController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::apiResource('categories', CategoryController::class);
Route::get('categories/{id}/children', [CategoryController::class, 'getChildren']);

Route::apiResource('courses', CourseController::class);
Route::apiResource('tags', TagController::class);


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [ProfileController::class, 'index']);
    Route::put('/profile', [ProfileController::class, 'update']);

});


    Route::post('/', [EnrollmentController::class, 'enrollStudent']);
    Route::get('/{studentId}', [EnrollmentController::class, 'getStudentEnrollments']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/refresh', [AuthController::class, 'refreshToken']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
        Route::get('/courses', [CourseController::class, 'index']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::get('/courses/{id}', [CourseController::class, 'show']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
});

Route::post('/payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/charge', [PaymentController::class, 'processPayment']);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('courses/{id}/videos', [VideoController::class, 'store']);  
    Route::get('courses/{id}/videos', [VideoController::class, 'index']);   
    Route::get('videos/{id}', [VideoController::class, 'show']);          
    Route::put('videos/{id}', [VideoController::class, 'update']);        
    Route::delete('videos/{id}', [VideoController::class, 'destroy']); 
});
