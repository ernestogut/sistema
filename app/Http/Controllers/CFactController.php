<?php

namespace App\Http\Controllers;

use App\C_fact;
use App\D_fact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CFactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera = C_fact::select('c_facts.id as num_doc', 'c_facts.id_tipo_comprobante', 'c_facts.id_almacen', 'c_facts.tipo_pago', 'c_facts.estado', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->where('fecha', '=', date('Y-m-d'))->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
        return $cabecera;
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
        $cabecera = $request->objeto_factura;
        $en_almacen = true;


        foreach ($detalle as $key => $value) {
            $producto_en_almacen = DB::table('inventarios')->where('id_producto', $value['codigo'])->where('id_almacen', $value['almacen'])->get();
            if(sizeof($producto_en_almacen) == 0){
                $en_almacen = false;
            break;
            }
        }


        if($en_almacen){
            DB::select("call insertarCabeceraFact(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",[$cabecera['id_serie'], $cabecera['id_tipo_comprobante'], $cabecera['cod_cliente'],$cabecera['ruc_cliente'],$cabecera['dir_cliente'],$cabecera['razon'],$cabecera['id_user'],$cabecera['fecha'],$cabecera['tipo_venta'],$cabecera['serie'],$cabecera['sub_total'],$cabecera['desc_global'],$cabecera['igv_total'],$cabecera['total'],$cabecera['id_almacen'], $cabecera['tipo_pago']]);
            if($cabecera['tipo_pago'] == 'efectivo'){
                DB::select("call generarCuentas(?,?,?)",[$cabecera['total'], $cabecera['fecha'], $cabecera['id_almacen']]);
            }
            $id_cabecera = C_fact::orderBy('id', 'desc')->first()->id;

            foreach ($detalle as $key => $value) {
                //# code...
                //var_dump($key);
                //var_dump($value);
                DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo'],$value['cantidad'],$value['almacen']]);
                
                $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo'])->sum('cantidad');

                DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo'],$suma_total]);
                if($value['codigo_padre'] != 0){
                    DB::connection("mysql")->statement("call disminuirInventarioAlm(?,?,?)",[$value['codigo_padre'],$value['cantidad'],$value['almacen']]);
                
                    $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_padre'])->sum('cantidad');

                    DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_padre'],$suma_total]);
                }
                //Artisan::call('cache:clear');
                /*DB::select("call disminuirInventario(?,?)",[$value['codigo'],$value['cantidad']]);*/
                $dfact = new D_fact();
                $dfact->id_fact = $id_cabecera;
                $dfact->codigo_producto = $value['codigo'];
                $dfact->descripcion_producto = $value['producto'];
                $dfact->precio_producto = $value['precio'];
                $dfact->cantidad_producto = $value['cantidad'];
                $dfact->almacen_producto = $value['almacen'];
                $dfact->descuento_producto = $value['descuento'];
                $dfact->total_producto = $value['total'];
                $dfact->save();
            }
            $ultimo_registro = C_fact::orderBy('id', 'desc')->first();
            return $ultimo_registro;
        }else{
           return response()->json(['error' => 'Se detectó un producto que no está en almacén'], 404);
        }
        
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $tipo_comprobante)
    {
        if(!$request->ajax()) return redirect('/');
        /*$cabecera = C_fact::find($id);
        return $cabecera;*/
        $cabecera = C_fact::select('c_facts.id as num_doc', 'c_facts.id_tipo_comprobante', 'c_facts.id_almacen', 'c_facts.tipo_pago', 'c_facts.estado', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->where('c_facts.id_tipo_comprobante', '=', $tipo_comprobante)->where('c_facts.fecha', '=', date('Y-m-d'))->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
        return $cabecera;
    }


    public function mostrarPorAlmacen($tipo_comprobante, $id_alm)
    {
        $cabecera = C_fact::select('c_facts.id as num_doc', 'c_facts.id_tipo_comprobante', 'c_facts.id_almacen', 'c_facts.tipo_pago', 'c_facts.estado', 'users.usuario', 'c_facts.serie', 'c_facts.folio', 'c_facts.fecha', 'c_facts.total')->join('users', 'c_facts.id_user', '=', 'users.id')->where('c_facts.id_tipo_comprobante', '=', $tipo_comprobante)->where('c_facts.fecha', '=', date('Y-m-d'))->where('c_facts.id_almacen', '=', $id_alm)->where('estado', 'habilitado')->orderBy('c_facts.id', 'desc')->get();
        return $cabecera;
    }
    public function mostrarVentasTipoPago($tipo_venta, $fecha, $almacen){
        $facturas_id = C_fact::select('id')->where('tipo_pago', '=', $tipo_venta)->where('fecha','=', $fecha)->where('id_almacen', '=', $almacen)->get();
        if(count($facturas_id) != 0){
            foreach($facturas_id as $fila) {
                $ventas_filtradas = DB::table('d_facts')->select('descripcion_producto', 'precio_producto', 'cantidad_producto', 'total_producto')->where('id_fact', '=', $fila->id)->get();
                foreach($ventas_filtradas as $fila_detalle) {
                    $myArray[] = $fila_detalle;
                }
            }
            return $myArray;
        }else{
            return response()->json(['error' => 'error'], 404);
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function edit(C_fact $c_fact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        $cabecera = C_fact::find($id);
        $cabecera->id_tipo_comprobante = $request->id_tipo_comprobante;
        $cabecera->cod_cliente = $request->cod_cliente;
        $cabecera->ruc_cliente = $request->ruc_cliente;
        $cabecera->dir_cliente = $request->dir_cliente;
        $cabecera->razon = $request->razon;
        $cabecera->id_users = $request->id_users;
        $cabecera->fecha = $request->fecha;
        $cabecera->tipo_venta = $request->tipo_venta;
        $cabecera->serie = $request->serie;
        $cabecera->folio = $request->folio;
        $cabecera->sub_total = $request->sub_total;
        $cabecera->desc_global = $request->desc_global;
        $cabecera->igv_total = $request->igv_total;
        $cabecera->total = $request->total;
        $cabecera->save();
        return $cabecera;

    }
    public function deshabilitarFactura(Request $request, $id)
    {
        if($request->tipo_pago == 'efectivo'){
            DB::select("call generarCuentasDeshabilitar(?,?,?)",[$request->total, $request->fecha, $request->id_almacen]);
        }
        $productos = D_fact::where('id_fact', $request->num_doc)->get();
        
        foreach ($productos as $key => $value) {
            //# code...
            //var_dump($key);
            //var_dump($value);

            DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_producto'],$value['cantidad_producto'],$value['almacen_producto']]);

            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_producto'])->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_producto'],$suma_total]);
            
        }
        $cabecera = C_fact::find($id);
        $cabecera->estado = 'deshabilitado';
        $cabecera->save();
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\C_fact  $c_fact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if($cabecera['tipo_pago'] == 'efectivo'){
            DB::select("call generarCuentas(?,?,?)",[$cabecera['total'], $cabecera['fecha'], $cabecera['id_almacen']]);
        }
        $productos = D_fact::where('id_fact', $id)->get();
        
        foreach ($productos as $key => $value) {
            //# code...
            //var_dump($key);
            //var_dump($value);

            DB::connection("mysql")->statement("call aumentarInventarioAlm(?,?,?)",[$value['codigo_producto'],$value['cantidad_producto'],$value['almacen_producto']]);

            $suma_total = DB::table('inventarios')->where('id_producto', '=', $value['codigo_producto'])->sum('cantidad');

            DB::connection("speed")->statement("call actualizarInventario(?,?)",[$value['codigo_producto'],$suma_total]);
            
        }

        $cabecera = C_fact::find($id);
        $cabecera->delete();
    }
}
