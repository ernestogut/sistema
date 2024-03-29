<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['guest']], function () {

    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/', 'Auth\LoginController@login')->name('login');

});


Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    //notificaciones
    Route::post('/notification/get', 'NotificationController@get');
    Route::get('/apertura', ['middleware' => 'main', function () { return view('apertura/apertura'); }])->name('contenido');
    Route::get('/principal', ['middleware' => 'apertura', function () { return view('contenido/contenido'); }])->name('principal');

    Route::group(['middleware' => ['Almacenero']], function () {
        /**
         * rutas categorias
         */
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar','CategoriaController@store' );
        Route::put('/categoria/actualizar','CategoriaController@update' );
        Route::put('/categoria/desactivar','CategoriaController@desactivar' );
        Route::put('/categoria/activar','CategoriaController@activar' );
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::get('/categoria/listarPdf', 'CategoriaController@listarPdf')->name('categorias_pdf');

        /**
         * rutas articulos
         */
        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar','ArticuloController@store' );
        Route::put('/articulo/actualizar','ArticuloController@update' );
        Route::put('/articulo/desactivar','ArticuloController@desactivar' );
        Route::put('/articulo/activar','ArticuloController@activar' );
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');


        /**
         * rutas proveedor
         */
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar','ProveedorController@store' );
        Route::put('/proveedor/actualizar','ProveedorController@update' );
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
        Route::get('/proveedor/listarPdf', 'ProveedorController@listarPdf')->name('proveedores_pdf');

        /**
         * rutas ingresos
         */
        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar','IngresoController@store' );
        Route::put('/ingreso/desactivar','IngresoController@desactivar' );
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/listarPdf', 'IngresoController@listarPdf')->name('ingresos_pdf');
        Route::get('/ingreso/BuscarUltimoIngreso', 'IngresoController@obtenerUltimoRegistroIngreso');


     });
    Route::group(['middleware' => ['Vendedor']], function () {

        /**
         * rutas clientes
         */
        /*Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store' );
        Route::put('/cliente/actualizar','ClienteController@update' );
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/listarPdf', 'ClienteController@listarPdf')->name('clientes_pdf');

        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');*/

         /**
         * rutas ventas
         */
        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar','VentaController@store' );
        Route::put('/venta/desactivar','VentaController@desactivar' );
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
        Route::get('venta/BuscarUltimaVenta', 'VentaController@obtenerUltimoRegistroVenta');

    });
    Route::group(['middleware' => ['Administrador']], function () {

        /**
         * ruta para obtener información de la empresa
         */

        Route::get('/informacion', 'DatoController@index');
        Route::put('/informacion/actualizar', 'DatoController@update');

        /**
         * rutas categorias
         */
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar','CategoriaController@store' );
        Route::put('/categoria/actualizar','CategoriaController@update' );
        Route::put('/categoria/desactivar','CategoriaController@desactivar' );
        Route::put('/categoria/activar','CategoriaController@activar' );
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');
        Route::get('/categoria/listarPdf', 'CategoriaController@listarPdf')->name('categorias_pdf');

        /**
         * rutas articulos
         */
        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar','ArticuloController@store' );
        Route::put('/articulo/actualizar','ArticuloController@update' );
        Route::put('/articulo/desactivar','ArticuloController@desactivar' );
        Route::put('/articulo/activar','ArticuloController@activar' );
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');

        /**
         * rutas proveedor
         */
        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar','ProveedorController@store' );
        Route::put('/proveedor/actualizar','ProveedorController@update' );
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');
        Route::get('/proveedor/listarPdf', 'ProveedorController@listarPdf')->name('proveedores_pdf');
        /**
         * rutas clientes
         */
        /*Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store' );
        Route::put('/cliente/actualizar','ClienteController@update' );
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');
        Route::get('/cliente/listarPdf', 'ClienteController@listarPdf')->name('clientes_pdf');*/


        /**
         * rutas roles
         */
        Route::resource('rol', 'RoleController');

        /**
         * rutas roles
         */
        //Route::get('/user', 'UserController@index');
        Route::get('/user/obtenerUsuarios', 'UserController@obtenerUsuarios');
        //Route::get('/user/{id}', 'UserController@show');
        //Route::post('/user/registrar','UserController@store' );
        //Route::put('/user/actualizar/{id}','UserController@update' );
        Route::get('/user/obtenerAlmacen/{id}/{id_almacen}','UserController@obtenerAlmacen' );
        //Route::put('/user/desactivar','UserController@desactivar' );
        //Route::put('/user/activar','UserController@activar' );
        Route::get('/user/logeado', 'UserController@usuarioLogeado'); 
        Route::resource('/user', 'UserController');


        /**
         * rutas ingresos
         */
        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar','IngresoController@store' );
        Route::put('/ingreso/desactivar','IngresoController@desactivar' );
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');
        Route::get('/ingreso/listarPdf', 'IngresoController@listarPdf')->name('ingresos_pdf');
        Route::get('/ingreso/BuscarUltimoIngreso', 'IngresoController@obtenerUltimoRegistroIngreso');

        /**
         * rutas ventas
         */
        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar','VentaController@store' );
        Route::put('/venta/desactivar','VentaController@desactivar' );
        Route::get('/venta/obtenerCabecera', 'VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}', 'VentaController@pdf')->name('venta_pdf');
        Route::get('/venta/BuscarUltimaVenta', 'VentaController@obtenerUltimoRegistroVenta');
        //Auth::routes();
    });

    


});
Route::post('almacen/generarInventario', 'SpeedController@generarInventario');
Route::get('almacen/{codigo}/cantidadesAlmacen', 'AlmacenController@cantidadesAlmacen');
Route::get('cierre_caja/{codigo}/verificarEstadoCaja', 'CierreCajaController@verificarEstadoCaja');
Route::get('cierre_caja/{codigo}/consultarCajaSeleccionada', 'CierreCajaController@consultarCajaSeleccionada');
Route::get('c_fact/{tipo_venta}/{fecha}/{almacen}/mostrarVentasTipoPago', 'CFactController@mostrarVentasTipoPago');
Route::get('speed/obtenerCategoriasProductos', 'SpeedController@obtenerCategoriasProductos');
Route::get('speed/obtenerPedidosPorMayor', 'SpeedController@obtenerPedidosPorMayor');
Route::post('delivery/subirVoucherPorPedido', 'DeliveryController@subirVoucherPorPedido');
Route::post('voucher_pedido_por_dia/subirVoucherPorDia', 'VoucherPedidoPorDiaController@subirVoucherPorDia');
Route::get('speed/obtenerProductosConPrecioPorMayor', 'SpeedController@obtenerProductosConPrecioPorMayor');
Route::get('speed/{id_pedido}/obtenerDetallePedidoPorMayor', 'SpeedController@obtenerDetallePedidoPorMayor');
Route::put('speed/{id_pedido}/cambiarEstadoPedido', 'SpeedController@cambiarEstadoPedido');
Route::put('cabecera_ingreso/{id_ingreso}/anularIngreso', 'CabeceraIngresoController@anularIngreso');
Route::post('speed/insertarPrecioPorMayor', 'SpeedController@insertarPrecioPorMayor');
Route::get('speed/{id_categoria}/obtenerProductosDeCategoria', 'SpeedController@obtenerProductosDeCategoria');
Route::get('delivery/{fecha}/obtenerPedidosPorFechaParaExportarExcel', 'DeliveryController@obtenerPedidosPorFechaParaExportarExcel');
Route::get('reporte_venta/{almacen}/mostrarVentasPorSemana', 'ReporteVentaController@mostrarVentasPorSemana');
Route::get('reporte_venta/{almacen}/obtenerVentasDiariasPorAlmacen', 'ReporteVentaController@obtenerVentasDiariasPorAlmacen');
Route::get('envio/{fecha}/rankingDepartamentosFecha', 'EnviosController@rankingDepartamentosFecha');
Route::get('envio/{fecha_inicio}/{fecha_fin}/rankingDepartamentosRango', 'EnviosController@rankingDepartamentosRango');
Route::get('envio/{fecha}/rankingProvinciasFecha', 'EnviosController@rankingProvinciasFecha');
Route::get('envio/{fecha_inicio}/{fecha_fin}/rankingProvinciasRango', 'EnviosController@rankingProvinciasRango');
Route::get('envio/{fecha}/rankingDistritosFecha', 'EnviosController@rankingDistritosFecha');
Route::get('envio/{fecha_inicio}/{fecha_fin}/rankingDistritosRango', 'EnviosController@rankingDistritosRango');
Route::get('proveedor/listarProveedorModal', 'ProveedorController@listarProveedorModal');
Route::get('cierre_caja/{almacen}/montoDiaAnterior', 'CierreCajaController@montoDiaAnterior');
Route::put('movimiento_caja/anularMovimiento', 'MovimientoCajaController@anularMovimiento');
Route::put('c_fact/{id}/deshabilitarFactura', 'CFactController@deshabilitarFactura');
Route::put('delivery/{id}/anularPedido', 'DeliveryController@anularPedido');
Route::get('delivery/{fecha}/obtenerPedidosPorFecha', 'DeliveryController@obtenerPedidosPorFecha');
Route::put('cierre_caja/{codigo}/cerrarCaja', 'CierreCajaController@cerrarCaja');
Route::post('detalle_ingreso/actualizarDetalleIngreso', 'DetalleIngresoController@actualizarDetalleIngreso');
Route::post('detalle_ingreso/completarIngreso', 'DetalleIngresoController@completarIngreso');
Route::put('detalle_traslado/{id_cabecera}/procesarTraslado', 'DetalleTrasladoController@procesarTraslado');
Route::put('detalle_traslado/{id_cabecera}/completarTraslado', 'DetalleTrasladoController@completarTraslado');
Route::get('speed/{almacen}/obtenerProductosPorAlmacen', 'SpeedController@obtenerProductosPorAlmacen');
Route::get('speed/{id_padre}/{almacen}/consultarVariacion', 'SpeedController@consultarVariacion');
Route::get('speed/{id_padre}/consultarVariacionTotal', 'SpeedController@consultarVariacionTotal');
Route::get('speed/{codigo_producto}/{almacen}/buscarProducto', 'SpeedController@buscarProducto');
Route::get('speed/{codigo_producto}/buscarProductoEnListaGeneral', 'SpeedController@buscarProductoEnListaGeneral');
Route::get('reporte/{fecha}/{almacen}/ventasPorFecha', 'ReporteVentaController@ventasPorFecha');
Route::get('reporte/{fecha_inicio}/{fecha_fin}/{almacen}/ventasPorFechaRango', 'ReporteVentaController@ventasPorFechaRango');
Route::get('movimiento_caja/{fecha}/{id_usuario}/reporteMovimientosPorUsuario', 'MovimientoCajaController@reporteMovimientosPorUsuario');
Route::get('movimiento_caja/{fecha_inicio}/{fecha_fin}/{id_usuario}/reporteMovimientosPorUsuarioPorFecha', 'MovimientoCajaController@reporteMovimientosPorUsuarioPorFecha');
Route::get('d_fact/{fecha}/{almacen}/rankingProductos', 'DFactController@rankingProductos');
Route::get('d_fact/{fecha_inicio}/{fecha_fin}/{almacen}/rankingProductosPorRango', 'DFactController@rankingProductosPorRango');
Route::get('d_fact/{fecha}/{almacen}/{id_categoria}/rankingProductosCategoria', 'DFactController@rankingProductosCategoria');
Route::get('d_fact/{fecha_inicio}/{fecha_fin}/{almacen}/{id_categoria}/rankingProductosPorRangoCategoria', 'DFactController@rankingProductosPorRangoCategoria');
Route::get('movimiento_caja/{almacen}/{fecha}/reporteDeMovimientos', 'MovimientoCajaController@reporteDeMovimientos');
Route::get('reporte_cierre/{fecha}/{almacen}/obtenerCierres', 'CierreCajaController@obtenerCierres');
Route::get('reporte_cierre/{fecha_inicio}/{fecha_fin}/{almacen}/obtenerCierresPorRango', 'CierreCajaController@obtenerCierresPorRango');
Route::get('c_fact/{tipo_comprobante}/{id_alm}/mostrarPorAlmacen', 'CFactController@mostrarPorAlmacen');
Route::get('cabecera_traslado/obtenerTrasladosPendientes', 'CabeceraTrasladoController@obtenerTrasladosPendientes');
//Route::get('inventario/{codigo}/cantidadesAlmacenInventario', 'InventarioController@cantidadesAlmacenInventario');
Route::resource('almacen', 'AlmacenController');
Route::post('producto/modificarInventario', 'ProductoController@modificarInventario')->name('producto.inventario'); 
Route::resource('producto', 'ProductoController');
Route::resource('proveedor', 'ProveedorController');
Route::get('cliente/{documento}/listarClientesModal', 'ClienteController@listarClientesModal')->name('cliente.listar');
Route::get('cliente/{id}/buscarCliente', 'ClienteController@buscarCliente')->name('cliente.buscar');
Route::get('proveedor/{id}/buscarProveedor', 'ProveedorController@buscarProveedor')->name('proveedor.buscar');
Route::get('tipo_comprobante/obtenerComprobantes', 'TipoComprobanteController@obtenerComprobantes')->name('tipo_comprobante.listar');
Route::resource('tipo_pago', 'TipoPagoController');
Route::resource('cliente', 'ClienteController');
Route::resource('c_fact', 'CFactController');
Route::resource('d_fact', 'DFactController');
Route::resource('cabecera_salida', 'CabeceraSalidaController');
Route::resource('detalle_salida', 'DetalleSalidaController');
Route::resource('voucher_pedido', 'VoucherPedidoController');
Route::resource('precio_delivery', 'PreciosDeliveryController');
Route::resource('delivery', 'DeliveryController');
Route::resource('tipo_comprobante', 'TipoComprobanteController');
Route::resource('comprobante', 'ComprobanteController');
Route::resource('serie_comprobante', 'SerieComprobanteController');
Route::resource('tipo_documento', 'TipoDocumentoController');
Route::get('serie_comprobante/{id}/{almacen}/listarSeries', 'SerieComprobanteController@listarSeries')->name('serie_comprobante.listar');
Route::resource('speed', 'SpeedController');
Route::resource('cabecera_ingreso', 'CabeceraIngresoController');
Route::resource('detalle_ingreso', 'DetalleIngresoController');
Route::resource('inventario', 'InventarioController');
Route::resource('cabecera_traslado', 'CabeceraTrasladoController');
Route::resource('detalle_traslado', 'DetalleTrasladoController');
Route::resource('cierre_caja', 'CierreCajaController');
Route::resource('movimiento_caja', 'MovimientoCajaController');
Route::resource('orden_tienda', 'PedidosSpeedController');
Route::resource('reporte_venta', 'ReporteVentaController');
Route::resource('cabecera_compra', 'CabeceraCompraController');
Route::resource('detalle_compra', 'DetalleCompraController');
//Route::get('/comprobante/{tipo_comprobante}', 'ComprobanteController@obtenerSeries');

//Route::get('/home', 'HomeController@index')->name('home');
