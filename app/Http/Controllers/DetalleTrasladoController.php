<?php

namespace App\Http\Controllers;

use App\DetalleTraslado;
use App\Inventario;
use App\CabeceraTraslado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleTrasladoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
        $detalle = $request->ventas;

        foreach ($detalle as $key => $value) {
            $detalle_traslado = new DetalleTraslado();
            $detalle_traslado->id_cabecera_traslado = $request->id_cabecera_traslado;
            $detalle_traslado->codigo_producto = $value['codigo'];
            $detalle_traslado->descripcion_producto = $value['producto'];
            $detalle_traslado->cantidad_producto = $value['cantidad'];
            $detalle_traslado->save();
        }
    }
    public function procesarTraslado(Request $request, $id_cabecera)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera = CabeceraTraslado::find($id_cabecera);
        $cabecera->estado = 'procesando';
        $cabecera->save();
    }
    public function completarTraslado(Request $request, $id_cabecera)
    {
        if(!$request->ajax()) return redirect('/');
        $detalle = DetalleTraslado::where('id_cabecera_traslado', $id_cabecera)->get();
        $cabecera = CabeceraTraslado::find($id_cabecera);
        
        

        foreach ($detalle as $key => $value) {
            $post = DB::connection("speed")->table('wp_posts')->where('ID', $value['codigo_producto'])->get();
            $producto = Inventario::where('id_producto', $value['codigo_producto'])->where('id_almacen', $cabecera->id_almacen_destino)->get();

            if(sizeof($producto) == 0){
                if($post[0]->post_parent == 0){
                    $prod_almacen = new Inventario();
                    $prod_almacen->id_producto = $value['codigo_producto'];
                    $prod_almacen->id_padre = $post[0]->post_parent;
                    $prod_almacen->id_almacen = $cabecera->id_almacen_destino;
                    $prod_almacen->cantidad = 0;
                    $prod_almacen->save();

                }else{

                    $registro_en_tabla = Inventario::where('id_producto', $post[0]->post_parent)->where('id_almacen', $cabecera->id_almacen_destino)->get();
                    if(sizeof($registro_en_tabla) == 0){
                        $prod_almacen = new Inventario();
                        $prod_almacen->id_producto = $value['codigo_producto'];
                        $prod_almacen->id_padre = $post[0]->post_parent;
                        $prod_almacen->id_almacen = $cabecera->id_almacen_destino;
                        $prod_almacen->cantidad = 0;
                        $prod_almacen->save();

                        


                        $prod_padre_almacen = new Inventario();
                        $prod_padre_almacen->id_producto = $post[0]->post_parent;
                        $prod_almacen->id_padre = $post[0]->post_parent;
                        $prod_padre_almacen->id_almacen = $cabecera->id_almacen_destino;
                        $prod_padre_almacen->cantidad = 0;
                        $prod_padre_almacen->save();


                    }else{
                        $prod_almacen = new Inventario();
                        $prod_almacen->id_producto = $value['codigo_producto'];
                        $prod_almacen->id_padre = $post[0]->post_parent;
                        $prod_almacen->id_almacen = $cabecera->id_almacen_destino;
                        $prod_almacen->cantidad = 0;
                        $prod_almacen->save();
                                
                                $suma_variaciones = DB::table('inventarios')->where('id_padre', '=', $prod_almacen->id_padre)->where('id_almacen', $prod_almacen->id_almacen)->sum('cantidad');
                                $prod_almacen_padre = Inventario::where('id_producto', $post[0]->post_parent)->where('id_almacen', $prod_almacen->id_almacen)->first();
                                $prod_almacen_padre->cantidad = $suma_variaciones;
                                $prod_almacen_padre->save();
                    }   
                }
                
            }
            DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo_producto'],$value['cantidad_producto'],$cabecera->id_almacen_origen]);
            DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_producto'],$value['cantidad_producto'],$cabecera->id_almacen_destino]);


                        
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_producto'])->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_producto'],$suma_total]);
            if($post[0]->post_parent != 0){
                DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$post[0]->post_parent,$value['cantidad_producto'],$cabecera->id_almacen_origen]);
                DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$post[0]->post_parent,$value['cantidad_producto'],$cabecera->id_almacen_destino]);

            //DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
        
            $suma_total = DB::table('inventarios')->where('id_producto', '=', $post[0]->post_parent)->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$post[0]->post_parent,$suma_total]);
        }    




            
        }
        
        $cabecera->estado = 'completado';
        $cabecera->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        /*$detalle_traslado = DetalleTraslado::select('detalle_traslados.id as num_doc_detalle', 'cabecera_traslados.id as num_doc_cabecera', 'detalle_traslados.codigo_producto', 'detalle_traslados.descripcion_producto', 'detalle_traslados.cantidad_producto')->join('cabecera_traslados', 'detalle_traslados.id_cabecera_traslado', '=', $id)->get();*/
        $detalle_traslado = DetalleTraslado::select('id as num_doc_detalle', 'id_cabecera_traslado as num_doc_cabecera')->where('id_cabecera_traslado', '=', $id)->get();


        $detalle_productos = DetalleTraslado::select('codigo_producto','descripcion_producto','cantidad_producto')->where('id_cabecera_traslado', '=', $id)->get();

        
        //dd($general);
        return response([$detalle_traslado[0], $detalle_productos]);
        /*$detalle_traslado = DetalleTraslado::select('detalle_traslados.id', 'cabecera_traslados.id_usuario','detalle_traslados.codigo_producto','detalle_traslados.descripcion_producto','detalle_traslados.cantidad_producto')->join('cabecera_traslados','detalle_traslados.id_cabecera_traslado', '=', $id)->get();*/
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function edit(DetalleTraslado $detalleTraslado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetalleTraslado $detalleTraslado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DetalleTraslado  $detalleTraslado
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetalleTraslado $detalleTraslado)
    {
        //
    }
}
