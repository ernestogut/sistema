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
        $usuarios = User::select('id', 'usuario')->get();
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

        try{
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;


            $persona->save();
            $user = new User();
            $user->usuario= $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->idrole = $request->idrole;

            $user->id = $persona->id;
            $user->save();

            DB::commit();

        } catch(Exception $e){
            DB::rollBack();
        }



    }
    public function usuarioLogeado()
    {
        $usuario = auth()->user();
        return $usuario;
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
}
