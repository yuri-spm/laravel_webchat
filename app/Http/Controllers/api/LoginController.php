<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function users()
    {
        $userLogged = Auth::user();

        $users = User::where('id', '!=', $userLogged->id)->get();

        if (!$userLogged) {
            return response()->json([
                'status' => false,
                'message' => 'Usuário não autenticado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status' => true,
            'message' => 'Usuário carregado com sucesso.',
            'data' => $users
        ], Response::HTTP_OK);
    }

    public function show(User $user)
    {
        return response()->json([
            'user'=> $user,
            Response::HTTP_OK
        ]);
    }
}
