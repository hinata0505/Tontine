<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'code_connexion' => 'required|string',
            'mot_de_passe' => 'required|string',
        ]);

        $user = User::where(
            'code_connexion',
            $request->code_connexion
        )->first();

        if (!$user || !Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants incorrects'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'user' => [
                'id_user' => $user->id_user,
                'code_connexion' => $user->code_connexion,
                'role' => $user->role,
                'id_memb' => $user->id_memb,
            ]
        ]);
    }
}