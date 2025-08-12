<?php

// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\CourseController;
// use App\Http\Controllers\ExerciseController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\VideoController;
// use App\Http\Controllers\CourseImageController;


// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/courses', [CourseController::class, 'index']);

//     Route::prefix('courses/{course}/videos')->group(function () {
//         Route::get('/', [VideoController::class, 'index']);
//     });

//     Route::prefix('videos')->group(function () {
//         Route::get('/{video}', [VideoController::class, 'show']);

//     });

//     Route::apiResource('courses', CourseController::class)->only(['index', 'show']);
//     Route::get('/categories', [CategoryController::class, 'index']);
//     Route::get('/categories/{id}', [CategoryController::class, 'show']);
//     Route::get('/courses/{id}/image', [CourseImageController::class, 'show']);

//     Route::middleware('role:admin')->group(function () {
//         Route::post('/categories', [CategoryController::class, 'store']);
//         Route::put('/categories/{id}', [CategoryController::class, 'update']);
//         Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);
//         Route::post('/courses', [CourseController::class, 'store']);
//         Route::put('/courses/{id}', [CourseController::class, 'update']);
//         Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

//         Route::prefix('courses/{course}/videos')->group(function () {
//             Route::post('/', [VideoController::class, 'store']);
//         });

//         Route::prefix('videos')->group(function () {
//             Route::put('/{video}', [VideoController::class, 'update']);
//             Route::delete('/{video}', [VideoController::class, 'destroy']);

//         });


//         Route::post('/courses/{id}/image', [CourseImageController::class, 'storeOrUpdate']);
//         Route::delete('/courses/{id}/image', [CourseImageController::class, 'destroy']);
//     });

//     Route::middleware('role:publisher')->group(function () {
//         Route::apiResource('courses', CourseController::class)->except(['index', 'show']);
//         Route::put('/categories/{id}', [CategoryController::class, 'update']);
//         Route::post('/courses', [CourseController::class, 'store']);
//         Route::put('/courses/{id}', [CourseController::class, 'update']);

//         Route::prefix('courses/{course}/videos')->group(function () {
//             Route::post('/', [VideoController::class, 'store']);
//         });

//         Route::prefix('videos')->group(function () {
//             Route::put('/{video}', [VideoController::class, 'update']);
//             Route::delete('/{video}', [VideoController::class, 'destroy']);


//         });
//         Route::post('/courses/{id}/image', [CourseImageController::class, 'storeOrUpdate']);
//         Route::delete('/courses/{id}/image', [CourseImageController::class, 'destroy']);
//     });

//     Route::middleware('role:student')->group(function () {


//     });
// });



use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseImageController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::get('/courses/{id}/image', [CourseImageController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/courses/{course}/videos', [VideoController::class, 'index']);
    Route::get('/videos/{video}', [VideoController::class, 'show']);

    Route::middleware('role:publisher|admin')->group(function () {

        Route::post('/courses', [CourseController::class, 'store']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::post('/courses/{id}/image', [CourseImageController::class, 'storeOrUpdate']);
        Route::delete('/courses/{id}/image', [CourseImageController::class, 'destroy']);

        Route::prefix('courses/{course}/videos')->group(function () {
            Route::post('/', [VideoController::class, 'store']);
        });
        Route::put('/videos/{video}', [VideoController::class, 'update']);
        Route::delete('/videos/{video}', [VideoController::class, 'destroy']);


        Route::put('/categories/{id}', [CategoryController::class, 'update']);
    });


    Route::middleware('role:admin')->group(function () {

        Route::post('/categories', [CategoryController::class, 'store']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);


        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);
    });
});
// powerd by nour
