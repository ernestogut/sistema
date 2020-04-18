<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $usuarios = User::select('users.id', 'users.usuario', 'users.nombre', 'users.apellido', 'roles.nombre as rol')->join('roles', 'users.idrole', 'roles.id')->get();
        return $usuarios;

    }

    public function obtenerUsuarios(Request $request)
    {
        //verifica si la peticion se la esta haciendo por ajax y si no es asi se lo redirige a /
        if(!$request->ajax()) return redirect('/');
        //listar todos los registros de la tabla categoria
        $usuarios = User::select('id', 'usuario', 'id_almacen')->get();
        return $usuarios;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
            $user = new User();
            $user->usuario= $request->usuario;
            $user->nombre= $request->nombre;
            $user->apellido= $request->apellido;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->idrole = $request->idrole;
            $user->save();
    }
    public function usuarioLogeado()
    {
        $usuario = auth()->user();
        return $usuario;
    }
    public static function obtenerAlmacen($id, $id_almacen)
    {
        $almancen = array();
        
        if($id_almacen != 0){
            $almacen = User::select('almacens.descripcion as almacen')->join('almacens', 'users.id_almacen', '=', 'almacens.id')->where('users.id', '=', $id)->get();
        }else{
            $sd = (object) array('almacen' => 'No definido');
            $almacen[0] = $sd;
        }
        return $almacen;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $usuario = User::find($id);
        $usuario->id_almacen = $request->id_almacen;
        $usuario->save();
    }

    /**
     * Desactivar the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    /**
     * activar the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
    public function destroy($id)
    {
        $usuario = User::find($id);
        $usuario->delete();
    }
}
