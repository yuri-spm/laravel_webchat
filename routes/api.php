<?php

use App\Http\Controllers\api\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Retornando informações direto no get
Route::get('/', function (Request $request) {
    return response()->json([
        'status' => true,
        'message' => 'API/V1'
    ], 200);
});


Route::post('/login', [LoginController::class, 'login'])
    ->name('login.index');

// public function login(Request $request){
//     return response()->json([
//             'email' => $request['email'],
//             'password' => $request['password']
//         ]
//     );
// }
