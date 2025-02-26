<?php

use App\Http\Controllers\api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Retornando informações direto no get
Route::get('/', function (Request $request) {
    return response()->json([
        'status' => true,
        'message' => 'API/V1'
    ], 200);
});


Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('/users', [LoginController::class, 'users'])->name('users');
});