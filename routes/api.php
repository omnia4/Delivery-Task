<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Task\TagController;
use App\Http\Controllers\Api\Menu\MenuController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Task\TaskController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\DeliveryController;
use App\Http\Controllers\Api\User\UserController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-code', [AuthController::class, 'verify']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::get('/nearest-delivery', [DeliveryController::class, 'getNearestDeliveryRepresentatives']);
    //Admin crud
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::post('/admin/users', [AdminController::class, 'store']);
    Route::get('/admin/users/{id}', [AdminController::class, 'show']);
    Route::put('/admin/users/{id}', [AdminController::class, 'update']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
    // send notification
    Route::post('/admin/send-notification', [AdminController::class, 'sendPushNotification']);
});
