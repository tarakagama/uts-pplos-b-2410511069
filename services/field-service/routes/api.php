<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FieldController;

// Endpoint Field Service
Route::get('/fields', [FieldController::class, 'index']);      
Route::post('/fields', [FieldController::class, 'store']);     
Route::get('/fields/{id}', [FieldController::class, 'show']);  
Route::put('/fields/{id}', [FieldController::class, 'update']);
Route::delete('/fields/{id}', [FieldController::class, 'destroy']);