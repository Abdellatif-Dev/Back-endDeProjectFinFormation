<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::apiResource("users", UserController::class);
Route::get("/showuser/{id}", [UserController::class,'showuser']);
Route::get("/showNotification/{id}", [UserController::class,'showNotification']);
Route::get('/notifications/read/{id}', [UserController::class, 'markNotificationsAsRead']);
Route::get("/logout", [AuthController::class,'logout']);
Route::post("/register", [AuthController::class,'register']);
Route::post("/login", [AuthController::class,'login']);
Route::post("/registerResto", [AuthController::class,'registerResto']);
Route::post("/updateProfile", [AuthController::class,'update_profile']);
Route::get("/aff", [AuthController::class,'aff']);
Route::apiResource('menu',MenuController::class);
Route::get("/menuPourUnResto", [MenuController::class,'menuPourUnResto']);
Route::apiResource('Commande',CommandeController::class);
Route::apiResource('comments',CommentController::class);
Route::post("/ajouterCommenteResto", [CommentController::class,'ajouterCommenteResto']);
Route::get("/showCommande/{id}", [CommandeController::class,'showCommande']);
Route::get("/showCommandeClient/{id}", [CommandeController::class,'showCommandeClient']);
Route::put('/commande-detail/{id}/status', [CommandeController::class, 'updateStatus']);
Route::put('/devoirs/{id}', [CommandeController::class, 'ChangeEtatDevoir']);



