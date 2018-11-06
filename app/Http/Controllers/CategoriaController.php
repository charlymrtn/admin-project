<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categorias = Categoria::all();

        return $categorias;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
          'nombre' => 'required|string|min:5|max:50',
          'descripcion' => 'nullable|string|min:5|max:256',
        ];
        $this->validate($request, $rules);

        $categoria = Categoria::create($request->only(['nombre','descripcion']));

        return $categoria;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
      $rules = [
        'nombre' => 'required|string|min:5|max:50',
        'descripcion' => 'sometimes|string|min:5|max:256',
      ];
      $this->validate($request, $rules);

      if (!$categoria->condicion) {
        $categoria->condicion = 1;
        $categoria->save();
      }

      $categoria->update($request->only('nombre','descripcion'));

      return $categoria;
    }

    //Funciones personalizadas

    public function activar(String $categoria_uuid)
    {

      $categoria = Categoria::findOrFail($categoria_uuid);

      if (!$categoria->condicion) {
        $categoria->condicion = 1;
        $categoria->save();
      }
    }

    public function desactivar(String $categoria_uuid)
    {

      $categoria = Categoria::findOrFail($categoria_uuid);

      if ($categoria->condicion) {
        $categoria->condicion = 0;
        $categoria->save();
      }
    }
}
