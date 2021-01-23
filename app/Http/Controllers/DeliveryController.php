<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\VoucherPedidoPorDia;
use App\VoucherPedido;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        //$pedidos = Delivery::select('users.nombre', 'deliveries.fecha')->join('users', 'deliveries.id_usuario', 'users.id')->distinct()->get();

        $pedidos = DB::table('vista_vouchers_por_dia')->select('fecha', 'conteo', 'conteo_cancelados', 'total_cobrar', 'voucher', 'id', 'total_monto_envios', 'pedidos_incompletos')->get();
        //$pedidos = Delivery::all();
        return $pedidos;
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

        $pedidos_por_fecha = Delivery::where('fecha', $request->fecha)->get();
        if(count($pedidos_por_fecha) == 0 ){
            $voucher_por_dia = new VoucherPedidoPorDia();
            $voucher_por_dia->fecha_dia = $request->fecha;
            $voucher_por_dia->save();
        }
        $pedido = new Delivery();
        $pedido->id_usuario = $request->id_usuario;
        $pedido->fecha = $request->fecha;
        $pedido->hora = $request->hora;
        $pedido->cliente = $request->cliente;
        $pedido->telefono = $request->telefono;
        $pedido->direccion = $request->direccion;
        $pedido->referencia = $request->referencia;
        $pedido->distrito = $request->distrito;
        $pedido->pedido = $request->pedido;
        $pedido->observacion_empresa = $request->observacion_empresa;
        $pedido->tipo_pago = $request->tipo_pago;
        $pedido->medio_recepcion = $request->medio_recepcion;
        $pedido->precio_productos = $request->precio_productos;
        $pedido->envio_productos = $request->envio_productos;
        $pedido->total_pedido = $request->total_pedido;
        $pedido->monto_pagado = $request->monto_pagado;
        $pedido->por_cobrar = $request->por_cobrar;
        $pedido->estado = 'pendiente';
        $pedido->save();

        $id_pedido = Delivery::orderBy('id', 'desc')->first()->id;
        $voucher_pedido = new VoucherPedido();
        $voucher_pedido->id_pedido = $id_pedido;
        $voucher_pedido->voucher = 'default.jpg';
        $voucher_pedido->save();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $delivery = Delivery::find($id);
        return $delivery;
    }
    public function obtenerPedidosPorFecha(Request $request, $fecha)
    {
        if(!$request->ajax()) return redirect('/');

        $pedidos_por_fecha = Delivery::select('deliveries.id', 'users.nombre as responsable', 'users.apellido', 'deliveries.fecha', 'deliveries.created_at', 'deliveries.hora', 'deliveries.cliente', 'deliveries.telefono', 'deliveries.direccion', 'deliveries.referencia', 'precios_deliveries.distrito as distrito', 'deliveries.pedido', 'deliveries.observacion_empresa', 'deliveries.observacion_delivery', 'tipo_pagos.nombre as tipo_pago','deliveries.medio_recepcion', 'deliveries.precio_productos', 'deliveries.envio_productos', 'deliveries.total_pedido', 'deliveries.monto_pagado', 'deliveries.por_cobrar', 'deliveries.estado', 'deliveries_imagenes.imagenes')->join('tipo_pagos', 'deliveries.tipo_pago', 'tipo_pagos.id')->join('users', 'deliveries.id_usuario', 'users.id')->join('precios_deliveries', 'deliveries.distrito', 'precios_deliveries.id')->join('deliveries_imagenes', 'deliveries.id', 'deliveries_imagenes.id_pedido')->where('deliveries.estado', '!=', 'anulado')->where('deliveries.fecha', $fecha)->orderBy('deliveries.id', 'desc')->get();


        foreach($pedidos_por_fecha as $valor){
            $valor->imagenes = explode(",", $valor->imagenes);
        }
        return $pedidos_por_fecha;
    }

    public function obtenerPedidosPorFechaParaExportarExcel(Request $request, $fecha){
        if(!$request->ajax()) return redirect('/');

        $pedidos_por_fecha = Delivery::select('deliveries.fecha', 'deliveries.cliente', 'deliveries.telefono', 'deliveries.direccion', 'deliveries.referencia', 'precios_deliveries.distrito as distrito', 'deliveries.observacion_empresa', 'tipo_pagos.metodo as tipo_pago', 'deliveries.por_cobrar')->join('tipo_pagos', 'deliveries.tipo_pago', 'tipo_pagos.id')->join('precios_deliveries', 'deliveries.distrito', 'precios_deliveries.id')->where('deliveries.estado', '!=', 'anulado')->where('deliveries.fecha', $fecha)->orderBy('deliveries.id', 'desc')->get();


        return $pedidos_por_fecha;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function edit(Delivery $delivery)
    {
        //
    }
    public function subirVoucherPorPedido(Request $request){
        /*
        if($request->hasFile('voucher')){
            $voucher = $request->file('voucher');
            $filename = $request->id_pedido . '_' . date('Y-m-d') . '.' . $voucher->getClientOriginalExtension();
            Image::make($voucher)->resize(500, 500)->save( public_path('/uploads/vouchers_por_pedido/') . $filename);
            $pedido = Delivery::find($request->id_pedido);
            $pedido->voucher = $filename;
            $pedido->save();
        }*/
        
        
        if($request->hasFile('file')){
            /*$voucher = VoucherPedido::where('id_pedido', $request->id_pedido)->where('voucher', 'default.jpg')->first();
            if($voucher){
                $voucher->delete();
            }*/
            $imageName = $request->file->getClientOriginalName();
            
            Image::make($request->file)->resize(500, 500)->save( public_path('/uploads/vouchers_por_pedido/') . $imageName);

            
            $voucher_existente = DB::table('voucher_pedidos')->where('voucher', $imageName)->get();
            if(count($voucher_existente) == 0){
                $voucher_pedido = new VoucherPedido();
                $voucher_pedido->id_pedido = $request->id_pedido;
                $voucher_pedido->voucher = $imageName;
                $voucher_pedido->save();
            }
            
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pedido = Delivery::find($id);

        if(auth()->user()->idrole != 4){
            $pedidos_por_fecha = Delivery::where('fecha', $request->fecha)->get();
            if(count($pedidos_por_fecha) == 0 ){
                $voucher_por_dia = new VoucherPedidoPorDia();
                $voucher_por_dia->fecha_dia = $request->fecha;
                $voucher_por_dia->save();
            }
            $pedido->id_usuario = $request->id_usuario;
            $pedido->fecha = $request->fecha;
            $pedido->cliente = $request->cliente;
            $pedido->telefono = $request->telefono;
            $pedido->direccion = $request->direccion;
            $pedido->referencia = $request->referencia;
            $pedido->distrito = $request->distrito;
            $pedido->pedido = $request->pedido;
            
            
            $pedido->medio_recepcion = $request->medio_recepcion;
            $pedido->precio_productos = $request->precio_productos;
            $pedido->envio_productos = $request->envio_productos;
            $pedido->total_pedido = $request->total_pedido;
            $pedido->monto_pagado = $request->monto_pagado;
            $pedido->por_cobrar = $request->por_cobrar;
        }
        $pedido->observacion_empresa = $request->observacion_empresa;
        $pedido->observacion_delivery = $request->observacion_delivery;
        $pedido->tipo_pago = $request->tipo_pago;
        $pedido->estado = $request->estado;
        $pedido->save();
        
    }
    public function anularPedido(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $pedido = Delivery::find($id);
        $pedido->estado = 'anulado';
        $pedido->save();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
