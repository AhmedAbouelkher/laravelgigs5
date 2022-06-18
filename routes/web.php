<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ListingController::class, 'index']);

// Users

Route::get('/register', [UserController::class, 'viewRegister'])->middleware('guest');
Route::get('/login', [UserController::class, 'viewLogin'])->name('login')->middleware('guest');

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);
Route::post('/users/logout', [UserController::class, 'logout']);


// Listing
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');

Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');

// manage listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

Route::get('/{listing}', [ListingController::class, 'show']);
