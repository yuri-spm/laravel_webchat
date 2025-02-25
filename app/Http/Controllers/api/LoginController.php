<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = Auth::user();
                $token = $user->createToken('api-token')->plainTextToken;

                return response()->json([
                    'token' => $token,
                    'user'  => $user,
                ], Response::HTTP_OK);

            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Login ou senha incorreta.',
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            Log::error('Erro no login: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro ao processar a requisição.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function users(Request $request, $id=null)
    {
        if($id){
            $user = User::find($id);

            if(!$user){
                return response()->json([
                    'status' => false,
                    "message"=> 'Usuário não encontrado.'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => true,
                "message" => 'Usuário carregado com sucesso',
                "data" => $user
            ], Response::HTTP_OK);
        }
        
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Token inválido ou não fornecido.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'Usuários carregados com sucesso.',
            'data' => User::all()
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Token inválido ou não fornecido.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout realizado com sucesso.'
        ], Response::HTTP_OK);
    }
}
