<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTipeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/index', [LandingController::class, 'index'])->name('landing.index');

Route::get('/posts/index', [PostController::class, 'index'])->name('posts.index');

Route::get('/posts/get-all', [PostController::class, 'prosesGetAll'])->name('proses.posts.get-all');
Route::post('/posts/add', [PostController::class, 'prosesAdd'])->name('proses.posts.add');
Route::post('/posts/edit', [PostController::class, 'prosesEdit'])->name('proses.posts.edit');
Route::post('/posts/delete', [PostController::class, 'prosesDelete'])->name('proses.posts.delete');

Route::get('/posts-tipe/index', [PostTipeController::class, 'index'])->name('posts-tipe.index');
Route::get('/posts-tipe/get-all', [PostTipeController::class, 'prosesGetAll'])->name('proses.posts-tipe.get-all');
Route::post('/posts-tipe/add', [PostTipeController::class, 'prosesAdd'])->name('proses.posts-tipe.add');
Route::post('/posts-tipe/edit', [PostTipeController::class, 'prosesEdit'])->name('proses.posts-tipe.edit');
Route::post('/posts-tipe/delete', [PostTipeController::class, 'prosesDelete'])->name('proses.posts-tipe.delete');
