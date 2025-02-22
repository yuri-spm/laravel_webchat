<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request){
        try {
            if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
                $user = Auth::user();
                $token = $request->user()->createToken('api-token')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                ], 200);

            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Login ou senha incorreta.',
                ], 404);
            }
        }catch (\Exception $e) {
            Log::error('Erro no login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro ao processar a requisição.',
            ], 500);
        }
    }
}
