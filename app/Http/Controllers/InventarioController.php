<?php

namespace App\Http\Controllers;

use App\Inventario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function cantidadesAlmacenInventario($codigo){
        $inventario = Inventario::select('almacens.id', 'almacens.codigo', 'almacens.descripcion','almacens.direccion', 'inventarios.cantidad as cantidad')->leftJoin('almacens','inventarios.id_almacen', '=', 'almacens.id')->where('inventarios.id_producto', '=', $codigo)->get();
        return $inventario;
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
        $prod_almacen = new Inventario();
        $prod_almacen->id_producto = $request->id_producto;
        $prod_almacen->id_almacen = $request->id_almacen;
        $prod_almacen->cantidad = $request->cantidad;
        $prod_almacen->save();
        
        $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

        DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
        //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
        //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
        //dd($suma_total);
        return $suma_total;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Inventario $inventario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventario $inventario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $prod_almacen = Inventario::find($id);
        $prod_almacen->id_producto = $request->id_producto;
        $prod_almacen->id_almacen = $request->id_almacen;
        $prod_almacen->cantidad = $request->cantidad;
        $prod_almacen->save();
        
        $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

        DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
        //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
        //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
        //dd($suma_total);
        return $suma_total;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inventario $inventario)
    {
        //
    }
}
