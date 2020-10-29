<?php

namespace App\Http\Controllers;
use App\Speed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SpeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //CON IMAGEN
        /*$productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock', 'm6.guid as imagen')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->leftJoin('wp_postmeta as m5', 'm5.post_id', '=', 'wp_posts.ID')->where('m5.meta_key', '=', '_thumbnail_id')->leftJoin('wp_posts as m6', 'm6.ID', '=', 'm5.meta_value')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'asc')->get();*/

        /* Traer pedidos wordpress desde laravel eloquent*/
        //$productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'wp_posts.post_parent as codigo_padre', 'm2.meta_value as precio', 'm4.meta_value as stock')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'desc')->get();


        /*Query para obtener productos de Wordpress con codigo padre*/

        //$productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'wp_posts.post_parent as codigo_padre', 'm2.meta_value as precio', 'm4.meta_value as stock')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'desc')->get();

        //Traer pedidos de wordpress desde la vista del phpmyadmin
        $productos = DB::connection("speed")->table('total_productos')->get();
        return $productos;
    }
    //Funciones de ventas al por mayor
    //Obtener productos con precio al por mayor para saber con exactitud cuales tienen y cuales no su precio respectivo.
    public function obtenerProductosConPrecioPorMayor()
    {
        $productos = DB::connection("speed")->table('total_productos')->select('codigo', 'producto', 'precio', 'precio_por_mayor')->get();
        return $productos;
    }
    public function insertarPrecioPorMayor(Request $request)
    {
        DB::connection("speed")->statement("call  insertarPrecioPorMayor(?,?)",[$request->id_producto,$request->precioPorMayor]);

    }
    public function obtenerDetallePedidoPorMayor($id_pedido){
        $detalle_pedido = DB::connection("speed2")->table('carrito_detalles')->select('id as doc', 'nombre as name', 'precio as price', 'cantidad', 'imagenes as images', 'codigo as id', 'es_variacion')->where('id_cabecera_carrito', $id_pedido)->get();
        foreach($detalle_pedido as $value){
            if(!$value->es_variacion){
                $value->images = json_decode($value->images);
            }else{
                $value->image = json_decode($value->images);
            }
        }
        return $detalle_pedido;
    }
    public function obtenerPedidosPorMayor()
    {
        $pedidos_por_mayor = DB::connection("speed2")->table("carrito_cabeceras")->select('carrito_cabeceras.id', 'users.name', 'users.apellido', 'carrito_cabeceras.observacion', 'carrito_cabeceras.total_carrito', 'carrito_cabeceras.nombre_envio', 'carrito_cabeceras.apellido_envio', 'carrito_cabeceras.dni_envio', 'carrito_cabeceras.telefono_envio', 'carrito_cabeceras.correo_envio', 'carrito_cabeceras.direccion_envio', 'carrito_cabeceras.departamento_envio', 'carrito_cabeceras.provincia_envio', 'carrito_cabeceras.distrito_envio', 'carrito_cabeceras.estado', 'carrito_cabeceras.created_at')->join('users', 'carrito_cabeceras.id_usuario', 'users.id')->orderBy('carrito_cabeceras.id', 'desc')->get();
        return $pedidos_por_mayor;
    }
    public function cambiarEstadoPedido(Request $request, $id_pedido){
        $pedido = DB::connection("speed2")->table("carrito_cabeceras")->where('id', $id_pedido)->update(['estado' => $request->opcion, 'observacion' => $request->observacion]);
    }
    // Fin de funciones para pedidos al por mayor
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function consultarVariacionTotal($id_padre){
        $variaciones = DB::connection("speed")->table('wp_posts')->select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'wp_posts.post_parent as codigo_padre', 'wp_posts.post_excerpt as variacion', 'm2.meta_value as precio', 'm4.meta_value as stock')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->where('wp_posts.post_parent', $id_padre)->where('wp_posts.post_type', 'product_variation')->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'desc')->get();

        return $variaciones;
    }
    public function obtenerCategoriasProductos(){
        $categorias = DB::connection("speed")->table('wp_terms as t')->select('t.term_id as id', 't.name as post_title', 't.slug as post_url')->leftJoin('wp_term_taxonomy as tt', 't.term_id', 'tt.term_id')->where('tt.taxonomy', 'product_cat')->orderBy('name')->get();
        return $categorias;
    }
    public function obtenerProductosDeCategoria($id_categoria){
        $productos_categoria = DB::connection("speed")->table('wp_posts as post')->select('ID')->join('wp_term_relationships as rs', 'rs.object_id', 'post.ID')->where('post.post_type', 'product')->where('post.post_status', 'publish')->where('rs.term_taxonomy_id', $id_categoria)->orderBy('post.post_title')->get();
        return $productos_categoria;
    }
    public function consultarVariacion($id_padre, $almacen)
    {
        $variaciones = DB::connection("speed")->table('variacion_productos')->where('codigo_padre', $id_padre)->where('almacen', $almacen)->get();

        return $variaciones;
    }
    public function buscarProducto($codigo_producto, $almacen)
    {
        $producto = Speed::where('sku', $codigo_producto)->where('almacen', $almacen)->get();

        if(sizeof($producto) != 0){
            return $producto;
        }else{
            return response()->json(['error' => 'El código no es válido'], 404);
        }
    }
    public function buscarProductoEnListaGeneral($codigo_producto)
    {
        $producto = Speed::where('sku', $codigo_producto)->get();

        if(sizeof($producto) != 0){
            return $producto;
        }else{
            return response()->json(['error' => 'El código no es válido'], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Speed  $speed
     * @return \Illuminate\Http\Response
     */
    public function show($codigo)
    {
        /*$productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock', 'm6.guid as imagen')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->leftJoin('wp_postmeta as m5', 'm5.post_id', '=', 'wp_posts.ID')->where('m5.meta_key', '=', '_thumbnail_id')->leftJoin('wp_posts as m6', 'm6.ID', '=', 'm5.meta_value')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->where('wp_posts.ID', '=', $codigo)->get();*/

        $productos = DB::connection("speed")->table('wp_posts')->select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock', 'm6.guid as imagen')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->leftJoin('wp_postmeta as m5', 'm5.post_id', '=', 'wp_posts.ID')->where('m5.meta_key', '=', '_thumbnail_id')->leftJoin('wp_posts as m6', 'm6.ID', '=', 'm5.meta_value')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->where('wp_posts.ID', '=', $codigo)->get();
        return $productos;
    }

    public function obtenerProductosPorAlmacen($almacen){
        $productos = Speed::where('almacen', $almacen)->get();
        return $productos;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Speed  $speed
     * @return \Illuminate\Http\Response
     */
    public function edit(Speed $speed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Speed  $speed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Speed $speed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Speed  $speed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Speed $speed)
    {
        //
    }
}
