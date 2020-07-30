<?php

namespace App\Http\Controllers;

use App\DetalleIngreso;
use App\Inventario;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleIngresoController extends Controller
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
                $en_almacen = true;
                /*$producto_en_almacen = DB::table('inventarios')->where('id_producto', $value['codigo'])->where('id_almacen', $value['almacen'])->get();*/
                foreach ($value['cantidades'] as $llave => $valor){
                    $producto_en_almacen = DB::table('inventarios')->where('id_producto', $value['codigo'])->where('id_almacen', $valor['id_almacen'])->get();
                    if(sizeof($producto_en_almacen) == 0){
                        if($value['codigo_padre'] == 0){
                            $prod_almacen = new Inventario();
                            $prod_almacen->id_producto = $value['codigo'];
                            $prod_almacen->id_padre = $value['codigo_padre'];
                            $prod_almacen->id_almacen = $valor['id_almacen'];
                            $prod_almacen->cantidad = $valor['cantidad'];
                            $prod_almacen->save(); 
                            
                            $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');
                
                            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);
                            //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
                            //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
                            //dd($suma_total);
                        }else{
                            $registro_en_tabla = Inventario::where('id_producto', $value['codigo_padre'])->where('id_almacen', $valor['id_almacen'])->get();
                            if(sizeof($registro_en_tabla) == 0){
                                $prod_almacen = new Inventario();
                                $prod_almacen->id_producto = $value['codigo'];
                                $prod_almacen->id_padre = $value['codigo_padre'];
                                $prod_almacen->id_almacen = $valor['id_almacen'];
                                $prod_almacen->cantidad = $valor['cantidad'];
                                $prod_almacen->save();

                                
                                $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');

                                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);

                                $prod_padre_almacen = new Inventario();
                                $prod_padre_almacen->id_producto = $value['codigo_padre'];
                                $prod_almacen->id_padre = $value['codigo_padre'];
                                $prod_padre_almacen->id_almacen = $valor['id_almacen'];
                                $prod_padre_almacen->cantidad = $valor['cantidad'];
                                $prod_padre_almacen->save();
                                

                                $suma_total_padre = DB::table('inventarios')->where('id_producto', '=', $prod_padre_almacen->id_producto)->sum('cantidad');

                                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_padre_almacen->id_producto,$suma_total_padre]);
                                //$suma_total = Inventario::sum('cantidad')->where('id_producto', '=', $prod_almacen->id_producto);
                                //$suma_total = DB::connection("mysql")->statement("call generarStockGeneral(?)",$prod_almacen->id_producto);
                                //dd($suma_total);
                                //return array($suma_total, $suma_total_padre) ;
                            }else{
                                $prod_almacen = new Inventario();
                                $prod_almacen->id_producto = $value['codigo'];
                                $prod_almacen->id_padre = $value['codigo_padre'];
                                $prod_almacen->id_almacen = $valor['id_almacen'];
                                $prod_almacen->cantidad = $valor['cantidad'];
                                $prod_almacen->save();

                                
                                $suma_total = DB::table('inventarios')->where('id_producto', '=', $prod_almacen->id_producto)->sum('cantidad');


                                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen->id_producto,$suma_total]);    
                                
                                $suma_variaciones = DB::table('inventarios')->where('id_padre', '=', $prod_almacen->id_padre)->where('id_almacen', $prod_almacen->id_almacen)->sum('cantidad');
                                $prod_almacen_padre = Inventario::where('id_producto', $value['codigo_padre'])->where('id_almacen', $prod_almacen->id_almacen)->first();
                                $prod_almacen_padre->cantidad = $suma_variaciones;
                                $prod_almacen_padre->save();



                                $suma_total_padre = DB::table('inventarios')->where('id_producto', '=', $prod_almacen_padre->id_producto)->sum('cantidad');


                                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$prod_almacen_padre->id_producto,$suma_total_padre]); 
                                //return array($suma_total, $suma_total_padre) ;

                            } 
                        }
                        //$en_almacen = false;
                    //break;
                    }else{
                        DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo'],$valor['cantidad'],$valor['id_almacen']]);
                        $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');
                        DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]); 
                        if($value['codigo_padre'] != 0){
                                DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$valor['cantidad'],$valor['id_almacen']]);

                            //DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
                        
                            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');
            
                            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
                        }    
                        //$en_almacen = true;
                    }
                    
                }
                //# code...
                //var_dump($key);
                //var_dump($value);
                $detalle_ingreso = new DetalleIngreso();
                $detalle_ingreso->id_cabecera_ingreso = $request->id_cabecera_ingreso;
                $detalle_ingreso->codigo_producto = $value['codigo'];
                $detalle_ingreso->descripcion_producto = $value['producto'];
                $detalle_ingreso->cantidades = json_encode($value['cantidades']);
                $detalle_ingreso->save();    
            }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function show($id_cabecera_ingreso)
    {
        $detalle_ingreso = DetalleIngreso::select('codigo_producto', 'descripcion_producto', 'cantidades')->where('id_cabecera_ingreso', '=', $id_cabecera_ingreso)->get();
        foreach($detalle_ingreso as $valor){
            $valor->cantidades = json_decode($valor->cantidades, true);
        }
        return($detalle_ingreso);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleIngreso $detalleIngreso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleIngreso  $detalleIngreso
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleIngreso $detalleIngreso)
    {
        //
    }
}
