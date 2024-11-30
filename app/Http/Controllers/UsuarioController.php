<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function funListar(){
        $usuarios = DB::select("SELECT * from users");

        return $usuarios;
    }

    public function funGuardar(Request $request){

        // validar
        /*
        $validated = $request->validate([
            "name" => "required|min:2|max:200",
            "email" => "required|unique:users|email"
        ]);
        */
        $validator = Validator::make($request->all(), [
            "name" => "required|min:2|max:200",
            "email" => "required|unique:users|email",
            "password" => "required"
        ]);
        
        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // guardar
        $usuario = new User();
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->save();

        return ["mensaje" => "Usuario Registrado"];
    }

    public function funMostrar($id){
        $usuario = User::find($id);
        
        if(!$usuario){
            return response()->json(["mensaje" => "Usuario no existe"], 404);
        }
        return response()->json($usuario, 200);
    }
    
    public function funModificar($id, Request $request){
        // validar
        $validator = Validator::make($request->all(), [
            "name" => "required|min:2|max:200",
            "email" => "required|unique:users,email,$id|email",
            "password" => "required"
        ]);
        
        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $usuario = User::find($id);
        if(!$usuario){
            return response()->json(["mensaje" => "Usuario no existe"], 404);
        }

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->password = $request->password;
        $usuario->update();

        return response()->json(["mensaje" => "Usuario Actualizado"], 201);
    }

    public function funEliminar($id){
        $usuario = User::find($id);

        $usuario->delete();

        return response()->json(["mensaje" => "Usuario Eliminado"], 201);

    }
}
