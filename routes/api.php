<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get("/saludo", function(){
    return "Hola desde el Taller de Creación de Api Rest Con Laravel";
});

Route::get("/tecnologias", function(){
    return ["PHP", "JAVA", "PYTHON"];
});

Route::get("/redes", function (){
    return [
        ["nombre" => "facebook", "logo" => "facebook.jpg", "usuarios" => ["cantidad" => "100000000", "america" => 982000, "europa" => 1500000]],
        ["nombre" => "Instagram", "logo" => "int.png"],
        ["nombre" => "Tiktok", "logo" => "tk.png"]
    ];
});


// AUTH

Route::prefix('/v1/auth')->group(function(){
    
    Route::post("login", [AuthController::class, "funLogin"]);
    Route::post("register", [AuthController::class, "funRegistro"]);
    
    Route::middleware('auth:sanctum')->group(function(){
        Route::get("profile", [AuthController::class, "funPerfil"]);
        Route::post("logout", [AuthController::class, "funSalir"]);
    });
});


Route::middleware('auth:sanctum')->group(function(){
    // CRUD USUARIOS
    Route::get("usuario", [UsuarioController::class, "funListar"]);
    Route::post("usuario", [UsuarioController::class, "funGuardar"]);
    Route::get("usuario/{id}", [UsuarioController::class, "funMostrar"]);
    Route::put("usuario/{id}", [UsuarioController::class, "funModificar"]);
    Route::delete("usuario/{id}", [UsuarioController::class, "funEliminar"]);

    Route::apiResource("categoria", CategoriaController::class);
    Route::apiResource("producto", ProductoController::class);

});

Route::get("no-autorizado", function(){
    return response()->json(["mensaje" => "Requiere TOKEN de Acceso"], 401);
})->name('login');
