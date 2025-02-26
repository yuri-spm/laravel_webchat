<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function users()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Usuário não autenticado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'Usuário carregado com sucesso.',
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
