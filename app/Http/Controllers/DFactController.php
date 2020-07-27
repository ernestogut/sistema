<?php

namespace App\Http\Controllers;

use App\D_fact;
use App\Speed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DFactController extends Controller
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

            DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo'],$value['cantidad'],$value['almacen']]);

            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]);
            
            //Artisan::call('cache:clear');
            /*DB::select("call disminuirInventario(?,?)",[$value['codigo'],$value['cantidad']]);*/
            $dfact = new D_fact();
            $dfact->id_fact = $request->id_cabecera;
            $dfact->codigo_producto = $value['codigo'];
            $dfact->descripcion_producto = $value['producto'];
            $dfact->precio_producto = $value['precio'];
            $dfact->cantidad_producto = $value['cantidad'];
            $dfact->almacen_producto = $value['almacen'];
            $dfact->descuento_producto = $value['descuento'];
            $dfact->total_producto = $value['total'];
            $dfact->save();
        }
        //return $detalle;
        //$dfact = new D_fact();
        /*foreach($detalle as $value){
            /*$dfact->id_fact = $request->id_cabecera;
            $dfact->codigo_producto = $value->codigo_producto;
            $dfact->descripcion_producto = $value->descripcion_producto;
            $dfact->precio_producto = $value->precio_producto;
            $dfact->cantidad_producto = $value->cantidad_producto;
            $dfact->descuento_producto = $value->descuento_producto;
            $dfact->almacen_producto = $value->almacen_producto;
            $dfact->total_producto = $value->total_producto;
            $dfact->save();
            echo json_decode($value)->codigo;
        }*/
        /*foreach ($request->respuesta as $venta) {
            dd($venta->cantidad);
        }
        $detalle = new D_fact();
        $detalle->id_fact = $request->id_fact;
        $detalle->codigo_producto = $request->codigo_producto;
        $detalle->descripcion_producto = $request->descripcion_producto;
        $detalle->precio_producto = $request->precio_producto;
        $detalle->cantidad_producto = $request->cantidad_producto;
        $detalle->descuento_producto = $request->descuento_producto;
        $detalle->almacen_producto = $request->almacen_producto;
        $detalle->total_producto = $request->total_producto;
        $detalle->save();
        return $detalle;*/
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $detalle = D_fact::select('d_facts.id','d_facts.codigo_producto', 'd_facts.descripcion_producto', 'd_facts.precio_producto', 'd_facts.cantidad_producto', 'd_facts.descuento_producto', 'almacens.descripcion as almacen', 'd_facts.total_producto')->join('almacens', 'd_facts.almacen_producto', 'almacens.id')->where('d_facts.id_fact', '=', $id)->get();
        //$detalle = D_fact::find($id);
        return $detalle;
    }
    public function rankingProductos($fecha, $almacen)
    {
        if($almacen != 0){
            /*$ranking = D_fact::select(DB::raw('codigo_producto, descripcion_producto, SUM(cantidad_producto) as cantidad'))->groupBy('codigo_producto')->where(DB::raw('DATE(created_at)'), $fecha)->where('almacen_producto', $almacen)->orderBy(DB::raw('sum(cantidad_producto)'), 'desc')->get();*/
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->where(DB::raw('DATE(d_facts.created_at)'), $fecha)->where('d_facts.almacen_producto', $almacen)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;

        }else{
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->where(DB::raw('DATE(d_facts.created_at)'), $fecha)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }
        
    }
    public function rankingProductosPorRango($fecha_inicio, $fecha_fin, $almacen)
    {
        if($almacen != 0){
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->whereBetween(DB::raw('DATE(d_facts.created_at)'), array($fecha_inicio, $fecha_fin))->where('d_facts.almacen_producto', $almacen)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }else{
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->whereBetween(DB::raw('DATE(d_facts.created_at)'), array($fecha_inicio, $fecha_fin))->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }
    }
    public function rankingProductosCategoria($fecha, $almacen, $id_categoria)
    {

        //->whereIn('codigo_producto', $array_productos)
        $productos_categoria = DB::connection("speed")->table('wp_posts as post')->select('ID')->join('wp_term_relationships as rs', 'rs.object_id', 'post.ID')->where('post.post_type', 'product')->where('post.post_status', 'publish')->where('rs.term_taxonomy_id', $id_categoria)->orderBy('post.post_title')->get();

        $array_productos[] = null;
        foreach($productos_categoria as $producto){
            array_push($array_productos, $producto->ID);
        }

        if($almacen != 0){
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->where(DB::raw('DATE(d_facts.created_at)'), $fecha)->where('d_facts.almacen_producto', $almacen)->whereIn('d_facts.codigo_producto', $array_productos)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }else{
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->where(DB::raw('DATE(d_facts.created_at)'), $fecha)->whereIn('d_facts.codigo_producto', $array_productos)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }
        
    }
    public function rankingProductosPorRangoCategoria($fecha_inicio, $fecha_fin, $almacen, $id_categoria)
    {
        $productos_categoria = DB::connection("speed")->table('wp_posts as post')->select('ID')->join('wp_term_relationships as rs', 'rs.object_id', 'post.ID')->where('post.post_type', 'product')->where('post.post_status', 'publish')->where('rs.term_taxonomy_id', $id_categoria)->orderBy('post.post_title')->get();
        $array_productos[] = null;
        foreach($productos_categoria as $producto){
            array_push($array_productos, $producto->ID);
        }
        
        if($almacen != 0){
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->whereBetween(DB::raw('DATE(d_facts.created_at)'), array($fecha_inicio, $fecha_fin))->where('d_facts.almacen_producto', $almacen)->whereIn('d_facts.codigo_producto', $array_productos)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }else{
            $ranking = D_fact::select(DB::raw('c_facts.estado, d_facts.codigo_producto, d_facts.descripcion_producto, SUM(d_facts.cantidad_producto) as cantidad'))->join('c_facts', 'd_facts.id_fact', 'c_facts.id')->groupBy('d_facts.codigo_producto')->whereBetween(DB::raw('DATE(d_facts.created_at)'), array($fecha_inicio, $fecha_fin))->whereIn('d_facts.codigo_producto', $array_productos)->where('c_facts.estado', 'habilitado')->orderBy(DB::raw('sum(d_facts.cantidad_producto)'), 'desc')->get();
            return $ranking;
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function edit(D_fact $d_fact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $detalle = D_fact::find($id);
        $detalle->id_fact = $request->id_fact;
        $detalle->codigo_producto = $request->codigo_producto;
        $detalle->descripcion_producto = $request->descripcion_producto;
        $detalle->precio_producto = $request->precio_producto;
        $detalle->cantidad_producto = $request->cantidad_producto;
        $detalle->descuento_producto = $request->descuento_producto;
        $detalle->almacen_producto = $request->almacen_producto;
        $detalle->total_producto = $request->total_producto;
        $detalle->save();
        return $detalle;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\D_fact  $d_fact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $detalle = D_fact::find($id);
        $detalle->delete();
    }
}
