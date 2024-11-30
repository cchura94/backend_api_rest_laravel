<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function funLogin(Request $request){
        
        
        $validado = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if($validado->fails()){
            return response()->json(["errors" => $validado->errors()], 422);
        }


        if(!Auth::attempt(["email" => $request->email, "password" => $request->password])){
            return response()->json(["mensaje" => "Credenciales Incorrectas"], 401);
        }

        // generar token
        $token = $request->user()->createToken("Token Login")->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "usuario" => $request->user()
        ], 201);
    }

    public function funRegistro(Request $request){
        
        return response()->json([],200);
    }

    public function funPerfil(Request $request){
        $usuario = $request->user();

        return response()->json($usuario,200);
    }

    public function funSalir(Request $request){
        
        $request->user()->tokens()->delete();

        return response()->json(["mensaje" => "Logout"], 200);
    }
}
