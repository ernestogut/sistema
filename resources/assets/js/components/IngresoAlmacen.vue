<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Ingreso de productos</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo ingreso</button>
                        </div>   
                    </div>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <datatable  :arrayItems="arrayFacturas" :cabeceras="cabecerasFactura"   :controlador="4" :funcionBoton="verFactura" :factura="true" :idTabla="'myTable'" v-else-if="initiated">
            </datatable>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ingreso</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form  action="" @submit.prevent="insertarCabecera()">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Almacen</label>
                                    <select v-model="objetoIngreso.id_almacen" class="form-control">
                                        <option disabled value="">Escoje un almacén</option>
                                        <option v-for="almacen in arrayAlmacenFijo" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Responsable</label>
                                    <select required="required" class="form-control" v-model="objetoIngreso.id_usuario">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
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
                                            <input  type="number" class="form-control input-sm" v-model="venta.cantidad">
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
                                    <datatable-productos @emitirEvProductos="recibirVenta" @emitirEvArrayAlm="recibirCantidadesAlmacen"  :abrirModalImagen="abrirModalImagen" :arrayAlmacenFijo="arrayAlmacenFijo" v-else-if="initiated"></datatable-productos>
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
                                    Detalle
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="card" style="width: 18rem;">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">ID: {{objetoDetalleFact.num_doc}}</li>
                                        <li class="list-group-item">Serie: {{objetoDetalleFact.serie}}</li>
                                        <li class="list-group-item">Folio: {{objetoDetalleFact.folio}}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCantidades">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Cantidades</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <spinner v-if="loading"></spinner>
                                <div class="card-body" v-else-if="initiated">
                                    <ul class="list-group">
                                        <li class="list-group-item cursor-pointer" v-for="almacen in arrayAlmacen" :key="almacen.id">
                                
                                            <span v-if="almacen.editable">{{almacen.id}}. Existen {{almacen.cantidad}} unidad(es) en el almacen de {{almacen.descripcion}}</span>
                                            <span v-else>
                                                {{almacen.id}}. No existen unidades en el almacen de {{almacen.descripcion}}
                                            </span>
                                            
                                                
                                            <!--<div>
                                                <span class="badge badge-primary badge-pill" id="eliminar" @click="eliminarTiempo(item, index)" v-show="!modoWcaRecibido || item.tiempoReal < 3000" >x</span>
                                            </div>-->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg show" id="modalImagen" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <spinner v-if="loadingImagen"></spinner>
                            <div class="modal-body d-flex flex-wrap justify-content-center align-items-center" id="dynamic-content" v-for="productoImagen in objetoProductoImagen" :key="productoImagen.key" v-else-if="initiatedImagen">
                                <img :src="productoImagen.imagen" style="width: 18rem;" class="img-fluid" alt=""/>
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
            initiatedImagen: false,
            loadingImagen: false,
            arrayClientes: [],
            arrayItems: [],
            arrayFacturas: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            ventas: [],
            objetoComprobante: {},
            objetoProductoImagen: {},
            cabecerasCliente: ['Acciones', '#', 'Código', 'Nombre', 'Tipo de documento', 'Num documento', 'Correo', 'Telef contacto'],
            iconos: 'icon-plus',
            cabecerasFactura: ['Acciones', 'Num documento', 'Almacén', 'Responsable', 'Fecha de emisión', 'Motivo', 'Observación'],
            comprobanteEscogido: '',
            tipoDocumento: null,
            //datos de la factura
            almacen_id: '',
            objetoIngreso: 
                {
                    id_almacen: null,
                    id_usuario: null,
                    fecha_emision: null,
                    motivo: '',
                    observacion: ''
                },
            controlador: 0, // 1 - productos, 2 - clientes, 4 -> facturas
            objetoDetalleFact: {},
            id_cabecera_ingreso: null,
            enAlmacen: false,
        }
    },
    computed:{
        usuarioLogeado(){
            return this.$store.getters.arrayUsuarioLogeado;
        },
        arrayUsuarios(){
            return this.$store.getters.arrayUsuarios;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        }
    },
    mounted(){
        //this.listarTComprobantes();
        this.listarIngresos()
        this.objetoIngreso.id_usuario = this.usuarioLogeado.id;
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
        $(this.$refs.tablaProductos).on("hidden.bs.modal", this.limpiarTablaProductos)
        $(this.$refs.tablaProveedores).on("hidden.bs.modal", this.limpiarTablaProveedores)
    },
    methods:{
        abrirModalImagen(producto){
            //var imagenProducto = {}
            this.loadingImagen = true
            $('#modalImagen').modal('show');
            axios.get(`speed/${producto.codigo}`).then(response=>{
                this.objetoProductoImagen = response.data
                this.loadingImagen = false
                this.initiatedImagen = true;
                //this.$emit('emitirImagen', imagenProducto);
            })
        },
        capturarComprobante(item){
            this.objetoComprobante = item
        },
        limpiarTabla(){
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoIngreso.id_almacen = null
            this.objetoIngreso.id_usuario = null
            this.objetoIngreso.fecha_emision = null
            this.objetoIngreso.motivo = ''
            this.objetoIngreso.observacion = ''
            
        },
        limpiarTablaProductos(){
            $('#myTableProductos').DataTable().destroy();
            //this.listarTipodeComprobante()
            this.listarIngresos()
        },
        limpiarTablaProveedores(){
            $('#myTableProveedores').DataTable().destroy();
            //this.listarTipodeComprobante()
            this.listarIngresos()
        },
        recibirVenta(venta){
            this.ventas = venta
        },
        recibirCantidadesAlmacen(almacen){
            this.arrayAlmacen = almacen
        },
        miTabla(){
            $( function () {
                $('#myTable').DataTable();
            } );
        },
        async listarItem(){
            this.loading = true
            await this.$store.dispatch('cargarProductos').then(()=>{
                this.loading = false;
                this.initiated = true;
                this.tablaProductos();
            });
        },
        listarClientes(){
            this.loading = true
            var urlItem = `/cliente/${this.objetoComprobante.id_tipo_doc}/listarClientesModal`;
            axios.get(urlItem).then(response=>{
                this.arrayClientes = response.data;
                this.loading = false;
                this.initiated = true;
                this.tablaClientes();
            })
        },
        listarIngresos(){
            var urlItem = '/cabecera_ingreso';
            this.loading = true
            axios.get(urlItem).then(response=>{
                this.arrayFacturas = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        listarTipodeComprobante(){
            this.loading = true
            axios.get(`/c_fact/${this.comprobanteEscogido}`).then(response=>{
                this.arrayFacturas = response.data;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        listarTComprobantes(){
            var urlItem = '/tipo_comprobante/obtenerComprobantes';
            axios.get(urlItem).then(response=>{
                this.arrayComprobantes = response.data;
            })
        },
        listarSeries(){
            axios.get(`/serie_comprobante/${this.comprobanteEscogido}/listarSeries`).then(response=>{
                if(response.data.length > 0){
                    this.arraySeries = response.data;
                    this.objetoFactura.id_serie = response.data[0].id;
                }else{
                    alert('Debes registrar series')
                }
                
            })
        },
        tablaClientes(){
            $( function () {
                $('#myTableClientes').DataTable({
                    searching: true
                });
            } );
        },
        tablaProductos(){
            $( function () {
                $('#myTableProductos').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalVenta(){
                this.objetoIngreso.id_almacen = this.arrayAlmacenFijo[0].id
                this.obtenerFecha()
                $('#modalVenta').modal('show');
        },
        abrirModalClientes(){
            this.listarClientes()
            this.controlador = 2
            $('#modalClientes').modal('show');
        },
        abrirModalAgregarProducto(){
            this.listarItem()
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
            document.getElementById(`producto${venta.codigo}`).className = ''
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        buscarCliente(codigo){
            axios.get(`/cliente/${codigo}/buscarCliente`).then((response)=>{
                this.objetoFactura.cod_cliente = response.data[0].codigo
                this.objetoFactura.ruc_cliente = response.data[0].num_documento
                this.objetoFactura.dir_cliente = response.data[0].direccion
                this.objetoFactura.razon = response.data[0].razon
            })
        },
        verFactura(item){
            this.objetoDetalleFact = item
            $('#modalInformacionFact').modal('show')
        },
        insertarCabecera(){
            axios.post('/cabecera_ingreso', this.objetoIngreso).then((response)=>{
                this.id_cabecera_ingreso = response.data
                for(var i = 0; i < this.ventas.length; i++){
                    this.ventas[i].almacen = this.objetoIngreso.id_almacen;
                }
                //this.ventas.almacen = this.objetoIngreso.id_almacen;
                axios.post('/detalle_ingreso', {'ventas': this.ventas, 'id_cabecera_ingreso': this.id_cabecera_ingreso}).then((response)=>{
                })
                $('#modalVenta').modal('hide');
                Vue.swal({
                    title: 'Ingreso exitoso!',
                    text: 'El ingreso ha sido realizado con éxito!',
                    icon: 'success'
                });
                $('#myTable').DataTable().destroy();
                this.listarIngresos();
            })
            .catch(error=>{
                Vue.swal({
                    title: 'Ingreso fallido!',
                    text: 'Ha habido un error al procesar el ingreso!',
                    icon: 'error'
                });
            })
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