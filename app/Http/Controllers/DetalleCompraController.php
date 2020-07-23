<?php

namespace App\Http\Controllers;

use App\DetalleCompra;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        if(!$request->ajax()) return redirect('/');

        //dd($request->id_cabecera);
        $detalle = $request->ventas;
        
        foreach ($detalle as $key => $value) {
            //# code...
            //var_dump($key);
            //var_dump($value);
            foreach ($value['cantidades'] as $llave => $valor){
                DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo'],$valor['cantidad'],$valor['id_almacen']]);
            }
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');
            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]); 
            if($value['codigo_padre'] != 0){
                foreach ($value['cantidades'] as $clave => $valo){
                    DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$valo['cantidad'],$valo['id_almacen']]);
                }
                //DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
            
                $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');

                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
            }  
            $detalle_compra = new DetalleCompra();
            $detalle_compra->id_compra = $request->id_cabecera;
            $detalle_compra->codigo_producto = $value['codigo'];
            $detalle_compra->descripcion_producto = $value['producto'];
            $detalle_compra->precio_producto = $value['precio'];
            $detalle_compra->cantidades = json_encode($value['cantidades']);
            //$detalle_compra->cantidad_producto = $value['cantidad'];
            $detalle_compra->descuento_producto = $value['descuento'];
            $detalle_compra->total_producto = $value['total'];
            $detalle_compra->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleCompra  $detalleCompra
     * @return \Illuminate\Http\Response
     */
    public function show($id_cabecera_compra)
    {
        $detalle_compra = DetalleCompra::select('codigo_producto', 'descripcion_producto', 'precio_producto', 'cantidades', 'total_producto')->where('id_compra', '=', $id_cabecera_compra)->get();
        foreach($detalle_compra as $valor){
            $valor->cantidades = json_decode($valor->cantidades, true);
        }
        return($detalle_compra);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleCompra  $detalleCompra
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleCompra $detalleCompra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleCompra  $detalleCompra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleCompra $detalleCompra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleCompra  $detalleCompra
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleCompra $detalleCompra)
    {
        //
    }
}
