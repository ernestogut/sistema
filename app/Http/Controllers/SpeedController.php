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

        /* Traer pedidos wordpress desde laravel eloquent
        $productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->orderBy('wp_posts.ID', 'desc')->get();*/

        //Traer pedidos de wordpress desde la vista del phpmyadmin
        $productos = Speed::all();
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
        $productos = Speed::select('wp_posts.ID as codigo', 'wp_posts.post_title as producto', 'm2.meta_value as precio', 'm4.meta_value as stock', 'm6.guid as imagen')->leftJoin('wp_postmeta as m2', 'm2.post_id', '=', 'wp_posts.ID')->where('m2.meta_key', '=', '_price')->leftJoin('wp_postmeta as m4', 'm4.post_id', '=', 'wp_posts.ID')->where('m4.meta_key', '=', '_stock')->leftJoin('wp_postmeta as m5', 'm5.post_id', '=', 'wp_posts.ID')->where('m5.meta_key', '=', '_thumbnail_id')->leftJoin('wp_posts as m6', 'm6.ID', '=', 'm5.meta_value')->whereIn('wp_posts.post_type', ['product', 'product_variation'])->where('wp_posts.post_status', '=', 'publish')->where('wp_posts.ID', '=', $codigo)->get();
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