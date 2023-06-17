<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('add', [\App\Http\Controllers\BlogPostController::class, 'add'])->name('addBlog');

Route::get('/blog-edit/{id}', [\App\Http\Controllers\BlogPostController::class, 'editBlog'])->name('blog-edit');

Route::post('/blog/{id}/update', [\App\Http\Controllers\BlogPostController::class, 'updateBlog']);

Route::post('status', [\App\Http\Controllers\BlogPostController::class, 'updateStatus'])->name('status');

Route::post('delete', [\App\Http\Controllers\BlogPostController::class, 'deleteBlog'])->name('delete');

Route::get('view/{id}', [\App\Http\Controllers\BlogPostController::class, 'view'])->name('view');

Route::post('addComment', [\App\Http\Controllers\BlogPostController::class, 'addComment'])->name('addComment');





















