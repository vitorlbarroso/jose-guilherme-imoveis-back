<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Responses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $createUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return Responses::OK('Usuário criado com sucesso!');
    }

    public function login(Request $request)
    {
        $hasUser = User::where('email', $request->email)->first();

        if (!$hasUser) {
            return Responses::BADREQUEST('Usuário ou senha incorretos!');
        }

        if (!Hash::check($request->password, $hasUser->password)) {
            return Responses::BADREQUEST('Usuário ou senha incorretos!');
        }

        $token = $hasUser->createToken('auth_token')->plainTextToken;

        return Responses::OK('Usuário autenticado com sucesso!', [
            'token' => $token
        ]);
    }
}
