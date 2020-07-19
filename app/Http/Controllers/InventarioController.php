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

        if($request->codigo_padre == 0){
            $prod_almacen = new Inventario();
            $prod_almacen->id_producto = $request->id_producto;
            $prod_almacen->id_padre = $request->codigo_padre;
            $prod_almacen->id_almacen = $request->id_almacen;
            $prod_almacen->cantidad = $request->cantidad;
            $prod_almacen->save();
            
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
            //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
            //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
            //dd($suma_total);
            return $suma_total;
        }else{
            $registro_en_tabla = Inventario::where('id_producto', $request->codigo_padre)->where('id_almacen', $request->id_almacen)->get();
            if(sizeof($registro_en_tabla) == 0){
                $prod_almacen = new Inventario();
                $prod_almacen->id_producto = $request->id_producto;
                $prod_almacen->id_padre = $request->codigo_padre;
                $prod_almacen->id_almacen = $request->id_almacen;
                $prod_almacen->cantidad = $request->cantidad;
                $prod_almacen->save();

                
                $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);

                $prod_padre_almacen = new Inventario();
                $prod_padre_almacen->id_producto = $request->codigo_padre;
                $prod_almacen->id_padre = $request->codigo_padre;
                $prod_padre_almacen->id_almacen = $request->id_almacen;
                $prod_padre_almacen->cantidad = $request->cantidad;
                $prod_padre_almacen->save();
                

                $suma_total_padre = DB::table('inventarios')->where('id_producto', '=', $prod_padre_almacen->id_producto)->sum('cantidad');

                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_padre_almacen->id_producto,$suma_total_padre]);
                //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
                //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
                //dd($suma_total);
                return array($suma_total, $suma_total_padre) ;
            }else{
                $prod_almacen = new Inventario();
                $prod_almacen->id_producto = $request->id_producto;
                $prod_almacen->id_padre = $request->codigo_padre;
                $prod_almacen->id_almacen = $request->id_almacen;
                $prod_almacen->cantidad = $request->cantidad;
                $prod_almacen->save();

                
                $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');


                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);    
                
                $suma_variaciones = DB::table('inventarios')->where('id_padre', '=', $prod_almacen->id_padre)->where('id_almacen', $prod_almacen->id_almacen)->sum('cantidad');
                $prod_almacen_padre = Inventario::where('id_producto', $request->codigo_padre)->where('id_almacen', $prod_almacen->id_almacen)->first();
                $prod_almacen_padre->cantidad = $suma_variaciones;
                $prod_almacen_padre->save();



                $suma_total_padre = DB::table('inventarios')->where('id_producto', '=', $prod_almacen_padre->id_producto)->sum('cantidad');


                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen_padre->id_producto,$suma_total_padre]); 
                return array($suma_total, $suma_total_padre) ;

            } 
        }

        
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

        if($request->codigo_padre == 0){
            $prod_almacen = Inventario::find($id);
            $prod_almacen->id_producto = $request->id_producto;
            $prod_almacen->id_padre = $request->codigo_padre;
            $prod_almacen->id_almacen = $request->id_almacen;
            $prod_almacen->cantidad = $request->cantidad;
            $prod_almacen->save();
            
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
            //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
            //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
            //dd($suma_total);
            return $suma_total;
        }else{
            $prod_almacen = Inventario::find($id);
            $prod_almacen->id_producto = $request->id_producto;
            $prod_almacen->id_padre = $request->codigo_padre;
            $prod_almacen->id_almacen = $request->id_almacen;
            $prod_almacen->cantidad = $request->cantidad;
            $prod_almacen->save();
            
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
            //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
            //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
            //dd($suma_total
                
            $suma_variaciones = DB::table('inventarios')->where('id_padre', '=', $prod_almacen->id_padre)->where('id_almacen', $prod_almacen->id_almacen)->sum('cantidad');
            $prod_almacen_padre = Inventario::where('id_producto', $request->codigo_padre)->where('id_almacen',$prod_almacen->id_almacen)->first();
            $prod_almacen_padre->cantidad = $suma_variaciones;
            $prod_almacen_padre->save();



            $suma_total_padre = DB::table('inventarios')->where('id_producto', '=', $prod_almacen_padre->id_producto)->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen_padre->id_producto,$suma_total_padre]);
            return array($suma_total, $suma_total_padre) ;
        }



        
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
