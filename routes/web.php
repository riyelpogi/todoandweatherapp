<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::post('/upload/todo', [TodoController::class, 'store'])->name('store');
Route::get('/', [TodoController::class, 'index'])->name('index');
Route::delete('/delete/todo/{id}', [TodoController::class, 'destroy'])->name('index');
