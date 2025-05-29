<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\PreprocessingController;
use App\Http\Controllers\NaiveBayesController;

// Halaman login default
Route::get('/', function () {
    return view('auth.login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (autentikasi wajib)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Halaman data statis (jika belum dipakai controller)
Route::get('/data', function () {
    return view('data.index');
})->middleware('auth')->name('data.index');

// Dataset (autentikasi wajib)
Route::prefix('dataset')->middleware('auth')->group(function () {
    Route::get('/full', [DatasetController::class, 'full'])->name('dataset.full');
    Route::get('/full-text', [DatasetController::class, 'fullText'])->name('dataset.fulltext');
    Route::delete('/delete-all', [DatasetController::class, 'deleteAll'])->name('dataset.deleteAll');
});

// Preprocessing (autentikasi wajib)
Route::prefix('preprocessing')->middleware('auth')->group(function () {
    Route::get('/', [PreprocessingController::class, 'index'])->name('preprocessing');
    Route::get('/dataclean', [PreprocessingController::class, 'dataclean'])->name('preprocessing.dataclean');
    Route::put('/update-sentimen/{id}', [PreprocessingController::class, 'updateSentimen'])->name('preprocessing.updateSentimen');
});

// Naive Bayes (autentikasi wajib)
Route::get('/naivebayes', [NaiveBayesController::class, 'index'])
    ->middleware('auth')
    ->name('naivebayes');
