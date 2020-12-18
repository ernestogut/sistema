<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Salida de productos</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nueva salida</button>
                        </div>   
                    </div>
                </div>
            </div>
            <div class="card-body" >
                <b-row>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Ordenar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="sortBySelect"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
                <template v-slot:first>
                  <option value>-- ninguno --</option>
                </template>
              </b-form-select>
              <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
                <option :value="false">Asc</option>
                <option :value="true">Desc</option>
              </b-form-select>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Buscar"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            label-for="filterInput"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                v-model="filter"
                type="search"
                id="filterInput"
                placeholder="Busca algo"
              ></b-form-input>
              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col sm="5" md="6" class="my-1">
          <b-form-group
            label="Por pagina"
            label-cols-sm="6"
            label-cols-md="4"
            label-cols-lg="3"
            label-align-sm="right"
            label-size="sm"
            label-for="perPageSelect"
            class="mb-0"
          >
            <b-form-select v-model="perPage" id="perPageSelect" size="sm" :options="pageOptions"></b-form-select>
                </b-form-group>
                </b-col>

                <b-col sm="7" md="6" class="my-1">
                <b-pagination
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                    align="fill"
                    size="sm"
                    class="my-0"
                ></b-pagination>
                </b-col>
            </b-row>

            <!-- Tabla principal -->
            <b-table
                show-empty
                small
                stacked="md"
                :busy="cargando"
                :items="arraySalidas"
                :fields="fields"
                :current-page="currentPage"
                :per-page="perPage"
                :filter="filter"
                :filterIncludedFields="filterOn"
                :sort-by.sync="sortBy"
                :sort-desc.sync="sortDesc"
                :sort-direction="sortDirection"
                @filtered="onFiltered"
                :emptyText="'No hay elementos para mostrar'"
                :emptyFilteredText="'No se han encontrado elementos para lo que buscas'"
            >
                <template v-slot:cell(index)="row">{{ row.index + 1 }}</template>

                <template v-slot:cell(actions)="row">
                <b-button size="sm"  @click="detalleIngreso(row.item)" class="mr-1">
                    <i class="icon-eye"></i>
                </b-button>
                </template>
                <template v-slot:table-busy>
                <div class="text-center text-danger my-2">
                    <b-spinner class="align-middle"></b-spinner>
                    <strong>Cargando...</strong>
                </div>
                </template>
            </b-table>
            </div>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo ingreso de productos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form  action="" @submit.prevent="insertarCabecera()">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Responsable</label>
                                    <select required="required" class="form-control" v-model="objetoSalida.id_usuario">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Fecha de emisión</label>
                                    <input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoSalida.fecha_emision">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Motivo</label>
                                    <input type="text" class="form-control" v-model="objetoSalida.motivo">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Documento</label>
                                    <input type="text" class="form-control" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Observación</label>
                                    <input type="text" class="form-control" v-model="objetoSalida.observacion">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Buscar producto</label>
                                        <form action="" @submit.prevent="codigoProducto.length > 0 ?abrirModalVariaciones(codigoProducto):''">
                                            <div class="input-group">
                                            
                                                <input type="text" class="form-control" name="SdnCode" id="buscarP" v-model="codigoProducto" placeholder="Código de barras" style="width: 80%;">
                                                    <span class="input-group-text buscador" @click="codigoProducto.length > 0 ?abrirModalVariaciones(codigoProducto):''"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            </div>    
                                        </form>
                                        
                                </div>
                            </div>
                                
                                
                                <div class="form-group col-md-4">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Lista de productos</button>
                                </div>
                        <div class="table-responsive scroll">   
                            <table class="table table-bordered table-sm ">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center align-middle">#</th>
                                        <th scope="col" class="text-center align-middle">Codigo</th>
                                        <th scope="col" class="text-center align-middle">Producto</th>
                                        <th scope="col" class="text-center align-middle" v-for="cabeceraAlm in arrayAlmacenFijo" :key="cabeceraAlm.id" >Cantidad {{cabeceraAlm.descripcion}}</th>
                                        <th scope="col" class="text-center align-middle">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(venta, index) in ventas" :key="venta.id">
                                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                        <td class="text-center align-middle">{{venta.codigo}}</td>
                                        <td class="text-center align-middle">{{venta.producto}}</td>
                                        <td class="text-center align-middle" v-for="cantidadAlm in venta.cantidades" :key="cantidadAlm.id"><input class="form-control" type="number" step="any"   v-model="cantidadAlm.cantidad"></td>
                                        <td class="text-center align-middle">
                                            <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(index)"><i class="icon-trash"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" v-on:keydown.enter.prevent>Guardar</button>
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
                                    <datatable-productos @emitirEvArrayAlm="recibirCantidadesAlmacen"  :abrirModalImagen="abrirModalImagen" :arrayAlmacenFijo="arrayAlmacenFijo" :ingreso="true" v-else-if="initiated"></datatable-productos>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalInformacionSalida" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
                    <div class="modal-dialog modal-xl">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    Detalle ingreso
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="moda-body">
                                    <div class="card">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center align-middle">#</th>
                                                            <th scope="col" class="text-center align-middle">Codigo</th>
                                                            <th scope="col" class="text-center align-middle">Producto</th>
                                                            <th scope="col" class="text-center align-middle" v-for="alm in arrayAlmacenFijo" :key="alm.id">Cantidad {{alm.descripcion}}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody >
                                                        <tr v-for="(detalle, index) in arraySalidaDetalle" :key="detalle.id">
                                                            <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                            <td class="text-center align-middle">{{detalle.codigo_producto}}</td>
                                                            <td class="text-center align-middle">{{detalle.descripcion_producto}}</td>
                                                            <td class="text-center align-middle" v-for="cantidad in detalle.cantidades" :key="cantidad.id">{{cantidad.cantidad}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade bd-example-modal-lg show" tabindex="-1" id="modalVariaciones" aria-hidden="true" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        
                        <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    Variaciones de {{productoVariacion}}
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <div class="modal-body">
                                <b-list-group>
                                    <b-list-group-item v-for="variacion in arrayVariaciones" :key="variacion.codigo"  class="d-flex justify-content-between align-items-center">
                                   
                                            <span>{{variacion.variacion}}</span>
                                            <span>{{variacion.stock}}</span>
                                            <div >
                                                <b-button
                                                    variant="warning"
                                                    size="sm"
                                                    @click="agregarProducto(variacion)"
                                                >
                                                    <i class="icon-plus"></i>
                                                </b-button>
                                                <b-button
                                                    variant="warning"
                                                    size="sm"
                                                    @click="abrirModalCantidades(variacion)"
                                                >
                                                    <i class="icon-eye"></i>
                                                </b-button>
                                                <b-button
                                                    variant="warning"
                                                    size="sm"
                                                    @click="abrirModalImagen(variacion)"
                                                >
                                                    <i class="icon-picture"></i>
                                                </b-button>
                                                </div>
                                                
                                    </b-list-group-item>
                                    <hr>
                                </b-list-group>
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
                <loading :active.sync="show" 
            :can-cancel="false" 
            
            :is-full-page="true"></loading>
    </main>
</template>
<script>
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
export default {
    data(){
        return{
            initiated: false,
            loading: false,
            initiatedImagen: false,
            loadingImagen: false,
            arrayClientes: [],
            arraySalidas: [],
            arraySalidaDetalle: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            //ventas: [],
            codigoProducto: '',
            objetoComprobante: {},
            objetoProductoImagen: {},
            iconos: 'icon-plus',
            fields: [
                { key: "id", label: "Num doc", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "responsable", label: "Vendedor", sortable: true,class: "text-center", class: "text-center"},
                { key: "fecha_emision", label: "Fecha de emisión", sortable: true, class: "text-center"},
                { key: "motivo", label: "Motivo", sortable: true, class: "text-center"},
                { key: "observacion", label: "Observacion", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones" , class: "text-center"}
            ],
            comprobanteEscogido: '',
            tipoDocumento: null,
            //datos de la factura
            almacen_id: '',
            objetoSalida: 
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
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            cargando: false,
        }
        //ingreso colocado, terminar con facturacion detallada y demás botones
    },
    components: {
            Loading
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
        },
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        sortOptions() {
      // Create an options list from our fields
        return this.fields
            .filter(f => f.sortable)
            .map(f => {
            return { text: f.label, value: f.key };
            });
        },
        ventas(){
            return this.$store.getters.tablaVenta;
        },
        arrayVariaciones(){
            return this.$store.getters.arrayVariaciones;
        },
        productoVariacion(){
            return this.$store.getters.productoVariacion;
        },
        show(){
            return this.$store.getters.show;
        }
    },
    mounted(){
        //this.listarTComprobantes();
        this.listarSalidas()
        this.objetoSalida.id_usuario = this.usuarioLogeado.id;
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
        $('#modalVariaciones').on('hidden.bs.modal', function () {
            $('#buscarP').trigger('focus');
        })
    },
    methods:{
        onFiltered(filteredItems) {
            // Trigger pagination to update the number of buttons/pages due to filtering
            this.totalRows = filteredItems.length;
            this.currentPage = 1;
        },
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
            var ventasT = []
            this.$store.dispatch('actualizarTablaVentas', ventasT)
            localStorage.setItem('ventas', JSON.stringify(ventasT)) 
            this.controlador = 4
            this.objetoSalida.id_almacen = null
            this.objetoSalida.fecha_emision = null
            this.objetoSalida.motivo = ''
            this.objetoSalida.observacion = ''
            this.codigoProducto = ''
            
        },
        agregarProducto(item){
            var obj = {}
            var controlador = false
            
            for(const i in item){
                if(i == 'codigo'){
                    obj.codigo = item[i]
                }
                if(i == 'producto'){
                    obj.producto = item[i]
                }
                if(i == 'codigo_padre'){
                    obj.codigo_padre = item[i]
                }
                if(i == 'precio'){
                    obj.precio = item[i]
                }
               
                    var objetoCantidad = {}
                    var arrayCantidad = []
                    var cantidadTotal = 0
                    for(var j = 0; j < this.arrayAlmacenFijo.length; j++){
                        objetoCantidad.id_almacen = this.arrayAlmacenFijo[j].id
                        objetoCantidad.cantidad = 0
                        objetoCantidad.nombre = this.arrayAlmacenFijo[j].descripcion
                        arrayCantidad.push(objetoCantidad)
                        objetoCantidad = {}
                    }
                    obj.cantidades = arrayCantidad;
                    for(var k = 0; k < obj.cantidades.length; k++){
                        cantidadTotal += obj.cantidades[k].cantidad  
                    }
                    obj.descuento = 0
                    obj.total = cantidadTotal*obj.precio
            }
            var ventasP = this.ventas;
            for(var j = 0; j < ventasP.length; j++){
                if(item.codigo == ventasP[j].codigo){
                    if(!this.ingreso){
                        ventasP[j].cantidad = parseInt(ventasP[j].cantidad)
                        ventasP[j].cantidad += 1
                        ventasP[j].total = ventasP[j].cantidad*ventasP[j].precio
                        controlador = true
                        break;
                    }
                }else{
                    controlador = false
                }
            }
            if(controlador){
                this.$store.dispatch('actualizarTablaVentas', ventasP);
                //this.$emit('emitirEvProductos', ventasP)
            }else{
                ventasP.push(obj)
                this.$store.dispatch('actualizarTablaVentas', ventasP);
                //this.$emit('emitirEvProductos', ventasP)
            }
            localStorage.setItem('ventas', JSON.stringify(this.ventas))
        },
        abrirModalVariaciones(codigo){
            this.$store.dispatch('actualizarShow', true)
           
            axios.get(`speed/${codigo}/buscarProductoEnListaGeneral`).then(response=>{
                
                var producto = response.data[0];
                this.$store.dispatch('actualizarProductoVariacion', producto.producto);
                this.consultarProductoSimple(producto);
                this.codigoProducto = ''
                this.$store.dispatch('actualizarShow', false)
            }).catch(error=>{ 
                console.log(error)
                this.$store.dispatch('actualizarShow', false)
                Vue.swal({
                        title: `${error.response.data.error}`,
                        text: 'Código no válido o no está registrado a un producto!',
                        icon: 'error',
                        onDestroy: ()=>{
                            this.codigoProducto = ''
                            $('#buscarP').trigger('focus');
                        }
                    }).then((result) => {
                        if (result.value) {
                            this.codigoProducto = ''
                            $('#buscarP').trigger('focus');
                        }
                    })
            })
        },
        consultarProductoSimple(producto){
            if(producto.situacion_producto == 'simple'){
                    this.agregarProducto(producto);
                    this.$store.dispatch('actualizarShow', false)
                }else if(producto.situacion_producto == 'variable'){
                    axios.get(`speed/${producto.codigo}/consultarVariacionTotal`).then(response=>{
                        this.$store.dispatch('actualizarVariaciones', response.data);
                        $('#modalVariaciones').modal('show');
                        this.$store.dispatch('actualizarShow', false)
                    })
                }
        },
        recibirCantidadesAlmacen(almacen){
            this.arrayAlmacen = almacen
        },
        async listarItem(){
            this.loading = true
            await this.$store.dispatch('cargarProductosTotales').then(()=>{
                this.loading = false;
                this.initiated = true;
            });
        },
        listarSalidas(){
            var urlItem = '/cabecera_salida';
            this.cargando = true
            axios.get(urlItem).then(response=>{
                this.arraySalidas = response.data;
                this.totalRows = this.arraySalidas.length;
                this.cargando = false;
            })
        },
        detalleIngreso(salida){
            axios.get(`detalle_salida/${salida.id}`).then((response)=>{
                this.arraySalidaDetalle = response.data;
                $('#modalInformacionSalida').modal('show');
                
            })
        },
        abrirModalVenta(){
                this.objetoSalida.id_almacen = this.arrayAlmacenFijo[0].id
                this.obtenerFecha()
                $('#modalVenta').modal('show');
                $('#modalVenta').on('shown.bs.modal', function () {
                    $('#buscarP').trigger('focus');
                })
        },
        abrirModalCantidades(producto){
            //this.loading = true
            $('#modalCantidades').modal('show');
            let me= this;
            var contador = 0
            axios.get(`almacen/${producto.codigo}/cantidadesAlmacen`).then(function (response){
                if(response.data.length < 1){
                    me.enAlmacen = false;
                    me.arrayAlmacen = me.arrayAlmacenFijo
                }else{
                    if(response.data.length == me.arrayAlmacenFijo.length){
                        me.enAlmacen = true;
                        me.arrayAlmacen = response.data;
                    }else{
                        for(var i = 0; i < me.arrayAlmacenFijo.length; i++){
                            contador = 0
                            for(var j = 0; j < response.data.length; j++){
                                if(response.data[j].id != me.arrayAlmacenFijo[i].id){
                                    
                                    contador += 1
                                    if(contador == response.data.length){
                                        me.arrayAlmacen = response.data;
                                        me.arrayAlmacen.push(me.arrayAlmacenFijo[i])
                                        me.arrayAlmacen[i].editable = false;
                                    }
                                }
                            }
                        }
                    }
                }
                me.$emit('emitirEvArrayAlm', me.arrayAlmacen);
            })
        },
        abrirModalAgregarProducto(){
            this.listarItem()
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        eliminarProductoTabla(index){
            var ventasT = this.ventas;
            ventasT.splice(index,1)
            this.$store.dispatch('actualizarTablaVentas', ventasT);
            //document.getElementById(`producto${venta.codigo}`).className = ''
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        insertarCabecera(){
            this.$store.dispatch('actualizarShow', true)
            axios.post('/cabecera_salida', this.objetoSalida).then((response)=>{
                this.id_cabecera_salida = response.data
                var productos = this.arrayProductos
                //this.ventas.almacen = this.objetoSalida.id_almacen;
                axios.post('/detalle_salida', {'ventas': this.ventas, 'id_cabecera_salida': this.id_cabecera_salida}).then((response)=>{
                    this.$store.dispatch('actualizarShow', false)
                    for(var k = 0; k < this.ventas.length; k++){
                        for(var l = 0; l < productos.length; l++){
                            if(this.ventas[k].codigo == productos[l].codigo){
                                for(var m = 0; m < this.ventas[k].cantidades.length; m++){
                                    productos[l].stock = productos[l].stock - parseInt(this.ventas[k].cantidades[m].cantidad);
                                }
                            }
                        }
                    }
                    Vue.swal({
                    title: 'Salida exitosa!',
                    text: 'La salida ha sido realizada con éxito!',
                    icon: 'success'
                    });
                    this.listarSalidas();
                    this.$store.dispatch('actualizarProductos', productos)
                     $('#modalVenta').modal('hide');
                })        
            })
            .catch(error=>{
                this.show = false
                Vue.swal({
                    title: 'Salida fallida!',
                    text: 'Ha habido un error al procesar la salida!',
                    icon: 'error'
                });
            })
        },
        obtenerFecha(){
            this.objetoSalida.fecha_emision = this.$moment().format("YYYY-MM-DD")
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