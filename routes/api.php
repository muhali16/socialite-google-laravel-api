<?php

use App\Http\Controllers\AuthGoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get("/v1/hsks/auth/google/request", [AuthGoogleController::class, "googleAuth"]);
// Route::get("/auth/google", [AuthGoogleController::class, "redirectToGoogle"]);
Route::get("/v1/hsks/auth/google/callback", [AuthGoogleController::class, "googleAuthCallback"]);

Route::middleware(['auth:sanctum'])->group(function (){
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index']);

    Route::delete("/auth/google/logout", [AuthGoogleController::class, 'logout']);
});

