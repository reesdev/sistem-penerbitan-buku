<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ManuscriptController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public Routes (Tanpa Auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes (Membutuhkan Login / Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Modul Penulis / Author (API Contract 2.1 & 2.2)
    Route::middleware('role:USER')->group(function () {
        Route::post('/manuscripts', [ManuscriptController::class, 'store']);
        Route::get('/manuscripts', [ManuscriptController::class, 'index']);
    });
});