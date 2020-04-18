<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
class RoleController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //verifica si la peticion se la esta haciendo por ajax y si no es asi se lo redirige a /
        if(!$request->ajax()) return redirect('/');
        //listar todos los registros de la tabla categoria
        $rol = Role::all();
        return $rol;
        // $categorias = DB::table('categorias')->paginate(2);
        // return $categorias;

    }

    public function selectRole(Request $request){

        $roles = Role::where('condicion', '=', '1')
        ->select('id','nombre')
        ->orderBy('nombre','asc')->get();

        return ['roles' => $roles];
    }

}
