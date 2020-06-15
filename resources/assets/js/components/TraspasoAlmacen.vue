<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Traslado de productos</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo traslado</button>
                        </div>   
                    </div>
                </div>
            </div>
            <b-card>
                <vue-datatable  :items="arrayTraslados" :fields="cabecerasTraslado" :funcionBoton="verFactura"  :controlador="4"  :factura="true" :cargando="loading">
                </vue-datatable>
            </b-card>
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
                                            <option v-for="almacen in arrayAlmacenFijo" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Almacen destino</label>
                                        <select v-model="objetoIngreso.id_almacen_destino" class="form-control">
                                            <option disabled value="">Escoje un almacén</option>
                                            <option v-for="almacen in arrayAlmacenFijo" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
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
                <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCantidades" style="overflow-y: scroll;">
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
            arrayTraslados: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            ventas: [],
            objetoComprobante: {},
            objetoProductoImagen: {},
            iconos: 'icon-plus',
            cabecerasTraslado: [
                { key: "num_documento", label: "Num documento", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "almacen_origen", label: "Almacén de origen", sortable: true, class: "text-center"},
                { key: "almacen_destino", label: "Almacén de destino", sortable: true, class: "text-center"},
                { key: "responsable", label: "Responsable", sortable: true, class: "text-center"},
                { key: "fecha_emision", label: "Fecha de emisión", sortable: true, class: "text-center"},
                { key: "motivo", label: "Motivo", sortable: true, class: "text-center"},
                { key: "observacion", label: "Observación", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ],
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
            id_cabecera_traslado: null,
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
        this.listarTraslados()
        this.objetoIngreso.id_usuario = this.usuarioLogeado.id
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
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
        tablaProductos(){
            $( function () {
                $('#myTableProductos').DataTable({
                    searching: true
                });
            } );
        },
        abrirModalVenta(){
                this.obtenerFecha()
                $('#modalVenta').modal('show');
        },
       
        abrirModalAgregarProducto(){
            this.listarItem()
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
            //document.getElementById(`producto${venta.codigo}`).className = ''
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