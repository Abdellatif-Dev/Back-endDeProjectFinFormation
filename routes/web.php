<?php

use App\Http\Controllers\PointageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return view('test');
});

Route::apiResource("pointage", PointageController::class); 

