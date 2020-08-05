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
