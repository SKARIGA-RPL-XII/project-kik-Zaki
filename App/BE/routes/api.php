<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth proccess
Route::prefix('/auth')->controller(AuthController::class)->group(function(){
    Route::post('login' , 'login');
    Route::post('register' , 'register');
    Route::post('logout' , 'logout')->middleware("auth:sanctum");
});

// Category Management
Route::resource('category', CategoryController::class)->only('store' , 'update' , 'destroy')->middleware(['admin' , 'auth:sanctum']);
Route::get('categories' , [CategoryController::class , 'index']);


// Banner Management
Route::resource('banners', BannerController::class)->only('store' , 'update' , 'destroy')->middleware(['admin' , 'auth:sanctum']);
Route::get('banners' , [BannerController::class , 'index']);

// Menu management
Route::resource('menus' , MenuController::class)->only('store' , 'update' , 'destroy' , 'show')->middleware(['admin' , 'auth:sanctum']);
Route::get('menu-admin' , [MenuController::class,'GetAllAdmin'])->middleware(['admin' , 'auth:sanctum']);
Route::get('menus' , [MenuController::class,'index']);


// Discount management
Route::resource('discounts' , DiscountController::class)->only('store' , 'update' , 'destroy' , 'applyDiscount')->middleware(['admin' , 'auth:sanctum']);
Route::get('discounts' , [DiscountController::class,'index']);


// Task management
Route::resource('tasks',TasksController::class)->middleware('auth:sanctum');

// Employe Management
Route::resource('employes', EmployeController::class)->middleware(['auth:sanctum','admin']);
