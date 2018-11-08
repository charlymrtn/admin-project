<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Rol;

class RolController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
      if (!$request->ajax()) return redirect('/');

      $buscar = $request->buscar;
      $criterio = $request->criterio;

      if ($buscar == '') {
        $roles = Rol::orderBy('nombre')->paginate(4);
      }else{
        $roles = Rol::where($criterio,'like','%'.$buscar.'%')->orderBy('nombre')->paginate(4);
      }

      return [
        'pagination' => [
          'total' => $roles->total(),
          'current_page' => $roles->currentPage(),
          'per_page' => $roles->perPage(),
          'last_page' => $roles->lastPage(),
          'from' => $roles->firstItem(),
          'to' => $roles->lastItem(),
        ],
        'roles' => $roles
      ];
  }
}
