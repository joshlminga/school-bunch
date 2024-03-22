<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ? Route Group for upload
Route::group(['prefix' => 'upfile'], function () {
    Route::middleware([RoleMiddleware::class . ':upload'])->group(function () {
        // ? Status Hierarchies
        Route::controller(App\Http\Controllers\ManageFileController::class)->group(function () {
            Route::get('/', 'index')->name('upfile');
            Route::post('/upload', 'uploadfile');
            Route::get('/all', 'myfiles')->name('upfile/all');
            Route::get('/file', 'thisfile');
        });
    });
});



require __DIR__ . '/auth.php';
