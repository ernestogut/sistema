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
                :items="arrayIngresos"
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
                                    <datatable-productos @emitirEvProductos="recibirVenta" @emitirEvArrayAlm="recibirCantidadesAlmacen"  :abrirModalImagen="abrirModalImagen" :arrayAlmacenFijo="arrayAlmacenFijo" :ingreso="true" v-else-if="initiated"></datatable-productos>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalInformacionIngreso" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
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
                                                        <tr v-for="(detalle, index) in arrayIngresoDetalle" :key="detalle.id">
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
            arrayIngresos: [],
            arrayIngresoDetalle: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            ventas: [],
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
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [],
            cargando: false
        }
        //ingreso colocado, terminar con facturacion detallada y demás botones
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
        }
    },
    mounted(){
        //this.listarTComprobantes();
        this.listarIngresos()
        this.objetoIngreso.id_usuario = this.usuarioLogeado.id;
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
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
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoIngreso.id_almacen = null
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
        async listarItem(){
            this.loading = true
            await this.$store.dispatch('cargarProductos').then(()=>{
                this.loading = false;
                this.initiated = true;
            });
        },
        listarIngresos(){
            var urlItem = '/cabecera_ingreso';
            this.cargando = true
            axios.get(urlItem).then(response=>{
                this.arrayIngresos = response.data;
                this.totalRows = this.arrayIngresos.length;
                this.cargando = false;
            })
        },
        detalleIngreso(ingreso){
            axios.get(`detalle_ingreso/${ingreso.id}`).then((response)=>{
                this.arrayIngresoDetalle = response.data;
                $('#modalInformacionIngreso').modal('show');
            })
        },
        abrirModalVenta(){
                this.objetoIngreso.id_almacen = this.arrayAlmacenFijo[0].id
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
        insertarCabecera(){
            axios.post('/cabecera_ingreso', this.objetoIngreso).then((response)=>{
                this.id_cabecera_ingreso = response.data
                var productos = this.arrayProductos
                //this.ventas.almacen = this.objetoIngreso.id_almacen;
                axios.post('/detalle_ingreso', {'ventas': this.ventas, 'id_cabecera_ingreso': this.id_cabecera_ingreso}).then((response)=>{
                    for(var k = 0; k < this.ventas.length; k++){
                        for(var l = 0; l < productos.length; l++){
                            if(this.ventas[k].codigo == productos[l].codigo){
                                for(var m = 0; m < this.ventas[k].cantidades.length; m++){
                                    productos[l].stock = productos[l].stock + parseInt(this.ventas[k].cantidades[m].cantidad);
                                }
                            }
                        }
                    }
                    this.$store.dispatch('actualizarProductos', productos)
                     $('#modalVenta').modal('hide');
                })

               
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