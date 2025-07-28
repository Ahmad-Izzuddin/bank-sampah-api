<?php

use App\Http\Controllers\WasteListController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function () {
    return 'API';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum','can:admin'])->group(function () {
    Route::apiResource('waste-lists', WasteListController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    // done
    Route::post('email-verification/send-email', [AuthController::class, 'resend']);
    // done
    Route::get('email-verification/status', [AuthController::class, 'status']);
});

Route::get('email/verify/{id}/{hash}', [AuthController::class, 'verify'])
     ->name('verification.verify')
     ->middleware('signed');

// ===== Password Reset =====
// Minta kirim link reset password
Route::post('password-reset/send-email', [AuthController::class, 'sendResetLinkEmail']);
// Proses reset password dengan token
Route::post('password-reset', [AuthController::class, 'reset']);

// Named route 'password.reset' agar Password::sendResetLink() berhasil membuat URL
Route::get('password/reset/{token}', function (Request $request, $token) {
    return response()->json([
        'token'   => $token,
        'email'   => $request->query('email'),
    ]);
})->name('password.reset');