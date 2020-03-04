<?php

namespace App\Http\Controllers;

use App\Speed;
use Illuminate\Http\Request;

class SpeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        /*$productos = Speed::select('wp_postmeta.meta_id','wp_postmeta.post_id','wp_postmeta.meta_key','wp_postmeta.meta_value','wp_posts.post_title as titulo')->join('wp_posts','wp_postmeta.post_id', '=', 'wp_posts.id')->where('post_type', '=', 'product')->get();
        //$imagenes = Speed::select('ID', 'guid')->where('post_type', '=', 'attachment')->get();

        /*$productos = Speed::select('ID', 'post_name', 'comment_status')->where('post_type', '=', 'product')->get();
        
        dd($productos[1]);*/



        //CON IMAGEN
        /*$productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock', 'm6.guid as imagen')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->leftJoin('wp_postmeta as m5', 'm5.post_id', '=', 'wp_posts.ID')->where('m5.meta_key', '=', '_thumbnail_id')->leftJoin('wp_posts as m6', 'm6.ID', '=', 'm5.meta_value')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'asc')->get();*/
        $productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'asc')->get();
        return $productos;
       /* $productos = Speed::all();
        dd($productos);*/
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Speed  $speed
     * @return \Illuminate\Http\Response
     */
    public function show(Speed $speed)
    {
        //
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
