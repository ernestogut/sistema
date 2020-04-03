<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Traslado de productos</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo</button>
                        </div>   
                    </div>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <datatable  :arrayItems="arrayTraslados" :cabeceras="cabecerasTraslado" :funcionBoton="verFactura"  :controlador="controlador"  :factura="true" :idTabla="'myTable'" v-else-if="initiated">
            </datatable>
            <!---->
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo traslado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form  action="" @submit.prevent="insertarCabecera()">
                            <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Almacen de origen</label>
                                        <select v-model="objetoIngreso.id_almacen_origen" class="form-control">
                                            <option disabled value="">Escoje un almacén</option>
                                            <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                        </select>
                                    </div>
                                    
                                    
                                    <!--<div class="col-md-3">
                                        
                                    </div>-->
                                    <div class="form-group col-md-6">
                                        <label>Almacen destino</label>
                                        <select v-model="objetoIngreso.id_almacen_destino" class="form-control">
                                            <option disabled value="">Escoje un almacén</option>
                                            <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                        </select>
                                    </div>
                                <div class="form-group col-md-6">
                                    <label>Responsable</label>
                                    <select required="required" class="form-control" v-model="objetoIngreso.id_usuario">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha de emisión</label>
                                    <input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoIngreso.fecha_emision">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Motivo</label>
                                    <input type="text" class="form-control" v-model="objetoIngreso.motivo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Documento</label>
                                    <input type="text" class="form-control" disabled>
                                </div>
                            </div>
                                <div class="form-group">
                                    <label>Observación</label>
                                    <input type="text" class="form-control" v-model="objetoIngreso.observacion">
                                </div>
                                <div class="form-group col-md-4">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Agregar productos</button>
                                </div>
                        <div class="table-responsive scroll">   
                            <table class="table table-bordered table-sm ">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center align-middle">#</th>
                                        <th scope="col" class="text-center align-middle">Codigo</th>
                                        <th scope="col" class="text-center align-middle">Producto</th>
                                        <th scope="col" class="text-center align-middle">Cantidad</th>
                                        <th scope="col" class="text-center align-middle">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(venta, index) in ventas" :key="venta.id">
                                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                        <td class="text-center align-middle">{{venta.codigo}}</td>
                                        <td class="text-center align-middle">{{venta.producto}}</td>
                                        <td class="text-center align-middle" >   
                                            <input type="number" class="form-control input-sm" v-model="venta.cantidad">
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(index)"><i class="icon-trash"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                    </div>  
                </div>
            </div>
            </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalProducto" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProductos">
                    <div class="modal-dialog modal-xl">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    Tabla de productos
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <spinner v-if="loading"></spinner>
                                    <datatable  :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos" @emitirEvProductos="recibirVenta" :listaVentasPadre="ventas" :controlador="controlador" :factura="false" :idTabla="'myTableProductos'" v-else-if="initiated"></datatable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalInformacionFact" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
                    <div class="modal-dialog modal-xl">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    Productos trasladados
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="card">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" v-for="producto in arrayDetalleTraslado.detalle" :key="producto.key">Has trasladado {{producto.cantidad_producto}} unidad(es) de {{producto.descripcion_producto}}, con codigo {{producto.codigo_producto}}  </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </main>
</template>
<script>

export default {
    data(){
        return{
            initiated: false,
            loading: false,
            arrayClientes: [],
            arrayItems: [],
            arrayTraslados: [],
            arrayUsuarios: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            ventas: [],
            objetoComprobante: {},
            cabeceras: ['Acciones', '#', 'Codigo', 'Producto', 'Precio', 'Cantidad'],
            cabecerasCliente: ['Acciones', '#', 'Código', 'Nombre', 'Tipo de documento', 'Num documento', 'Correo', 'Telef contacto'],
            iconos: 'icon-plus',
            cabecerasTraslado: ['Acciones', '#', 'Num documento', 'Almacén origen', 'Almacén destino', 'Responsable', 'Fecha de emisión', 'Motivo', 'Observación'],
            usuarioLogueado: {},
            comprobanteEscogido: '',
            tipoDocumento: null,
            //datos de la factura
            almacen_id: '',
            objetoIngreso: 
                {
                    id_almacen_origen: null,
                    id_almacen_destino: null,
                    id_usuario: null,
                    fecha_emision: null,
                    motivo: '',
                    observacion: ''
                },
            controlador: 0, // 1 - productos, 2 - clientes, 4 -> facturas
            array:[],
            arrayDetalleTraslado: {
                cabecera: null,
                detalle: null
            },
            id_cabecera_traslado: null
        }
    },
    mounted(){
        this.listarTraslados()
        this.listarUsuarios();
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
    },
    methods:{
        limpiarTabla(){
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoIngreso.id_almacen_origen = null
            this.objetoIngreso.id_almacen_destino = null
            this.objetoIngreso.id_usuario = null
            this.objetoIngreso.fecha_emision = null
            this.objetoIngreso.motivo = ''
            this.objetoIngreso.observacion = ''
            
        },
        limpiarTablaProductos(){
            $('#myTableProductos').DataTable().destroy();
            this.listarTipodeComprobante()
        },
        limpiarTablaProveedores(){
            $('#myTableProveedores').DataTable().destroy();
            this.listarTipodeComprobante()
        },
        recibirVenta(venta){
            this.ventas = venta
        },
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        listarItem(){
            this.loading = true
            var urlItem = '/speed';
            axios.get(urlItem).then(response=>{
                this.arrayItems = response.data;
                this.loading = false;
                this.initiated = true;
                this.tablaProductos();
            })
        },
        listarTraslados(){
            var urlItem = '/cabecera_traslado';
            this.loading = true
            axios.get(urlItem).then(response=>{
                this.arrayTraslados = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoIngreso.id_usuario = response.data.id
                
            })
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
            })
        },
        tablaProductos(){
            $( function () {
                $('#myTableProductos').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalVenta(){
                this.listarUsuarios();
                this.seleccionarAlmacen();
                this.obtenerFecha()
                $('#modalVenta').modal('show');
        },
       
        abrirModalAgregarProducto(){
            this.listarItem()
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.objetoIngreso.id_almacen = response.data[0].id
                me.arrayAlmacen = response.data;
            })
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        verFactura(item){
            axios.get(`/detalle_traslado/${item.num_documento}`).then((response)=>{
                this.arrayDetalleTraslado.cabecera = response.data[0];
                this.arrayDetalleTraslado.detalle = response.data[1];
            })

            
            $('#modalInformacionFact').modal('show')
        },
        insertarCabecera(){
            if(this.objetoIngreso.id_almacen_origen == this.objetoIngreso.id_almacen_destino){
                alert('El almacen de destino no puede ser el mismo que el de origen')
            }else{
                axios.post('/cabecera_traslado', this.objetoIngreso).then((response)=>{
                    this.id_cabecera_traslado = response.data
                    for(var i = 0; i < this.ventas.length; i++){
                        this.ventas[i].almacen_origen = this.objetoIngreso.id_almacen_origen;
                        this.ventas[i].almacen_destino = this.objetoIngreso.id_almacen_destino;
                    }
                    axios.post('/detalle_traslado', {'ventas': this.ventas, 'id_cabecera_traslado': this.id_cabecera_traslado}).then((response)=>{
                    })
                    $('#modalVenta').modal('hide');
                    Vue.swal({
                        title: 'Traslado exitoso!',
                        text: 'El traslado ha sido procesado con éxito!',
                        icon: 'success'
                    });
                    $('#myTable').DataTable().destroy();
                    this.listarTraslados();
                })
                .catch(error=>{
                    Vue.swal({
                        title: 'Traslado fallido',
                        text: 'El traslado no ha podido ser procesado',
                        icon: 'error'
                    });
                })
            }
        },
        obtenerFecha(){
            this.objetoIngreso.fecha_emision = this.$moment().format("YYYY-MM-DD")
        }
    },
}
</script>

<style scoped>

li{
    list-style-type: none;
}
.scroll{
    height: 300px;
    overflow-y: scroll
}
.buscador{
    cursor: pointer;
}
</style>