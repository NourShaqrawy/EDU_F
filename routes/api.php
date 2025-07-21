<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExerciseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\CourseImageController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/courses', [CourseController::class, 'index']);

    Route::prefix('courses/{course}/videos')->group(function () {
        Route::get('/', [VideoController::class, 'index']);
    });

    Route::prefix('videos')->group(function () {
        Route::get('/{video}', [VideoController::class, 'show']);
        // روابط الاختبارات الخاصة بالفيديو
        Route::get('/{video}/exercises', [ExerciseController::class, 'getExercisesByVideo']);
    });

    Route::apiResource('courses', CourseController::class)->only(['index', 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/courses/{id}/image', [CourseImageController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

        Route::prefix('courses/{course}/videos')->group(function () {
            Route::post('/', [VideoController::class, 'store']);
        });

        Route::prefix('videos')->group(function () {
            Route::put('/{video}', [VideoController::class, 'update']);
            Route::delete('/{video}', [VideoController::class, 'destroy']);


            Route::post('/{video}/exercises', [ExerciseController::class, 'store']);
            Route::put('/exercises/{exercise}', [ExerciseController::class, 'update']);
            Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy']);
        });


        Route::get('/exercises', [ExerciseController::class, 'index']);
        Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);
        Route::post('/courses/{id}/image', [CourseImageController::class, 'storeOrUpdate']);
        Route::delete('/courses/{id}/image', [CourseImageController::class, 'destroy']);
    });

    Route::middleware('role:publisher')->group(function () {
        Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);

        Route::prefix('courses/{course}/videos')->group(function () {
            Route::post('/', [VideoController::class, 'store']);
        });

        Route::prefix('videos')->group(function () {
            Route::put('/{video}', [VideoController::class, 'update']);
            Route::delete('/{video}', [VideoController::class, 'destroy']);


            Route::post('/{video}/exercises', [ExerciseController::class, 'store']);
            Route::put('/exercises/{exercise}', [ExerciseController::class, 'update']);
            Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy']);
        });
        Route::post('/courses/{id}/image', [CourseImageController::class, 'storeOrUpdate']);
        Route::delete('/courses/{id}/image', [CourseImageController::class, 'destroy']);
    });

    Route::middleware('role:student')->group(function () {
        Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);


        Route::post('/exercises/{exercise}/submit', [ExerciseController::class, 'submit']);
        Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);
        Route::get('/videos/{video}/exercises', [ExerciseController::class, 'getExercisesByVideo']);
    });
});
    