<?php

use App\Http\Controllers\CrypterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\VirustotalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
/*
Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');
*/
Route::get('/crypter', function () {
    return view('files.crypter');
})->middleware(['auth', 'verified'])->name('crypter');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/file/new/{folder}', [FileController::class, 'store'])->middleware(['auth', 'verified'])->name('file.post');
Route::get('/home', [FileController::class, 'index'])->middleware(['auth', 'verified'])->name('home');
Route::delete('/file/delete/{id}', [FileController::class, 'deleteFile'])->middleware(['auth', 'verified'])->name('file.delete');
Route::get('/file/crypt/{id}', [FileController::class, 'cryptFile'])->middleware(['auth', 'verified'])->name('file.crypt');

Route::get('/test/create', [VirustotalController::class, 'createTest'])->middleware(['auth', 'verified'])->name('test.create');
Route::get('/test/list', [VirustotalController::class, 'index'])->middleware(['auth', 'verified'])->name('virustotal.get');
Route::post('/api/new', [VirustotalController::class, 'requestApi'])->middleware(['auth', 'verified'])->name('api.new');
Route::delete('/test/delete/{id}', [VirustotalController::class, 'deleteTest'])->middleware(['auth', 'verified'])->name('test.delete');
Route::get('/test/edit/{id}', [VirustotalController::class, 'editTest'])->middleware(['auth', 'verified'])->name('test.edit');
Route::put('/test/update/{id}', [VirustotalController::class, 'updateTest'])->middleware(['auth', 'verified'])->name('test.update');

require __DIR__.'/auth.php';


 