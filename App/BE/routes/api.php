<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DutyScheduleController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth proccess
Route::prefix('/auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware("auth:sanctum");
});

Route::post('updateProfile', [UserController::class,'updateProfile'])->middleware("auth:sanctum");
Route::middleware('auth:sanctum')->get('/user/me', [UserController::class, 'me']);

// Category Management
Route::resource('category', CategoryController::class)->only('store', 'update', 'destroy')->middleware(['admin', 'auth:sanctum']);
Route::get('categories', [CategoryController::class, 'index']);


// Banner Management
Route::resource('banners', BannerController::class)->only('store', 'update', 'destroy')->middleware(['admin', 'auth:sanctum']);
Route::get('banners', [BannerController::class, 'index']);

// Menu management
Route::resource('menus', MenuController::class)->only('store', 'update', 'destroy', 'show')->middleware(['admin', 'auth:sanctum']);
Route::get('menu-admin', [MenuController::class, 'GetAllAdmin'])->middleware(['admin', 'auth:sanctum']);
Route::get('menus', [MenuController::class, 'index']);


// Discount management
Route::resource('discounts', DiscountController::class)->only('store', 'update', 'destroy', 'applyDiscount')->middleware(['admin', 'auth:sanctum']);
Route::get('discounts', [DiscountController::class, 'index']);


// Task management
Route::resource('tasks', TasksController::class)->middleware('auth:sanctum');

// Employe Management
Route::resource('employes', EmployeController::class)->middleware(['auth:sanctum', 'admin']);

// Management Badge
Route::resource('badges', BadgeController::class)->middleware(['auth:sanctum']);

Route::resource('tables', TableController::class);
Route::resource('rooms', RoomController::class);

Route::resource('transactions', TransactionController::class);
Route::resource('events', EventController::class)->middleware("auth:sanctum");
Route::resource('duty-schedules', DutyScheduleController::class)->middleware("auth:sanctum");
