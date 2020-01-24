<?php

namespace App\Http\Controllers;

use App\Proveedor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()){
            return redirect('/');
        }else{
            $proveedor = Proveedor::All();
            return $proveedor;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $proveedor = new Proveedor();
        #if($request->hasFile('imagen')){
         #   $proveedor['imagen']=$request->file('imagen')->store('uploads', 'public');
        #}
        $proveedor->nombre = $request->nombre;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->correo = $request->correo;
        $proveedor->telefono_contacto = $request->telefono_contacto;
        #$proveedor->imagen = $request->imagen;
        $proveedor->save();
        #$proveedor = request()->all();
        #$proveedor = request()->except('_token');
        #proveedor::insert($proveedor);
        #return response()->json($proveedor);
        return $proveedor;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = Producto::find($id);
        return $proveedor;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $proveedor = Proveedor::find($id);
        #if($request->hasFile('imagen')){
         #   $proveedor['imagen']=$request->file('imagen')->store('uploads', 'public');
        #}
        $proveedor->nombre = $request->nombre;
        $proveedor->num_documento = $request->num_documento;
        $proveedor->correo = $request->correo;
        $proveedor->telefono_contacto = $request->telefono_contacto;
        #$proveedor->imagen = $request->imagen;
        $proveedor->save();
        #$proveedor = request()->all();
        #$proveedor = request()->except('_token');
        #proveedor::insert($proveedor);
        #return response()->json($proveedor);
        return $proveedor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->delete();
    }
}
