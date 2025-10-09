<?php

use App\Http\Controllers\JobDetailsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [JobDetailsController::class, 'index'])->name('job.create');
Route::post('/job/store', [JobDetailsController::class, 'storeJob'])->name('job.store');

Route::get('/resume/upload/{job}', [JobDetailsController::class, 'upload'])->name('resume.upload');
Route::post('/resume/store', [JobDetailsController::class, 'storeResume'])->name('resume.store');