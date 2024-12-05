<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::orderBy("id", "desc")->with(["categoria"])->paginate(10);

        return response()->json($productos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);

        // subida de imagenes
        $direccion_imagen = "";
        if($file = $request->file("imagen")){
            $url_imagen = time() . "-" . $file->getClientOriginalName();
            $file->move("imagenes/", $url_imagen);
            $direccion_imagen = "imagenes/". $url_imagen;
        }
        
        // guardar
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        $producto->imagen = $direccion_imagen;
        $producto->save();

        return response()->json(["mensaje" =>"producto registrado"], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::findOrFail($id);
        
        return response()->json($producto, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "nombre" => "required",
            "categoria_id" => "required"
        ]);

        // subida de imagenes
        $direccion_imagen = "";
        
        // guardar
        $producto = Producto::findOrFail($id);
        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        
        if($file = $request->file("imagen")){
            $url_imagen = time() . "-" . $file->getClientOriginalName();
            $file->move("imagenes/", $url_imagen);
            $direccion_imagen = "imagenes/". $url_imagen;
            $producto->imagen = $direccion_imagen;
        }

        $producto->update();

        return response()->json(["producto registrado"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        $producto->delete();

        return response()->json(["mensaje" => "Producto eliminado"]);
    }
}
