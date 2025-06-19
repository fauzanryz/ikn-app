<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\NaiveBayesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', [PageController::class, 'index']);
Route::post('/cekSentimen', [PageController::class, 'cekSentimen'])->name('cekSentimen');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Dataset
Route::prefix('dataset')->middleware('auth')->group(function () {
    Route::get('/full', [DatasetController::class, 'full'])->name('dataset.full');
    Route::get('/full-text', [DatasetController::class, 'fullText'])->name('dataset.fulltext');
    Route::delete('/delete-all', [DatasetController::class, 'deleteAll'])->name('dataset.deleteAll');
    Route::post('/import', [DatasetController::class, 'import'])->name('dataset.import');
});

// Preprocessing
Route::prefix('preprocessing')->middleware('auth')->group(function () {
    Route::get('/', [PreprocessingController::class, 'index'])->name('preprocessing');
    Route::get('/dataclean', [PreprocessingController::class, 'dataclean'])->name('preprocessing.dataclean');
    Route::put('/update-sentimen/{id}', [PreprocessingController::class, 'updateSentimen'])->name('preprocessing.updateSentimen');
});

// Naive Bayes
Route::get('/naivebayes', [NaiveBayesController::class, 'index'])
    ->middleware('auth')
    ->name('naivebayes');

// User Management
Route::resource('users', UserController::class)->middleware('auth');
