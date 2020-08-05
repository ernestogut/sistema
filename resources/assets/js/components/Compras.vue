<template>
  <main class="main">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Compras de la empresa</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalCompra()">Nueva compra</button>
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
                :busy="loading"
                :items="arrayCompras"
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
                <b-button size="sm"  @click="detalleCompra(row.item)" class="mr-1">
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
            <div class="modal fade bd-example-modal-lg1" id="modalCompra" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Nueva compra</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="card-body">
                            <form class="kt-form kt-form-label-right" action="" @submit.prevent="insertarCabeceraCompra()">
                                <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                                    <div class="row justify-content-start">
                                        <div class="col-2 col-form-label">Cod. Proveedor</div>
                                        <div class="input-group col-4">
                                            <span class="input-group-text buscador" @click="(objetoCompra.cod_proveedor.length > 0)?buscarProveedor(objetoCompra.cod_proveedor):abrirModalProveedores()"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            <input type="text" class="form-control" name="SdnCode" v-model="objetoCompra.cod_proveedor" placeholder="Buscar" style="width: 80%;">
                                        </div>
                                        <div class="col-2 col-form-label">Num.Doc</div>
                                        <div class="col-4"><input type="text" name="Id" class="form-control" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Tipo de documento</div>
                                            <div class="input-group col">
                                            <input type="text" name="RUC" class="form-control" :value="objetoCompra.ruc_proveedor" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-form-label">Dirección</div>
                                        <div class="col-4"><input type="text" name="Direccion" class="form-control" :value="objetoCompra.dir_proveedor" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Razón Social</div>
                                        <div class="col-4"><input type="text" name="SdnName" class="form-control" :value="objetoCompra.razon"></div>
                                        <div class="col-2 col-form-label">Fecha Emi</div>
                                        <div class="col-4"><input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoCompra.fecha"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Responsable</div>
                                        <div class="col-4">
                                            <select required="required" class="form-control" v-model="objetoCompra.id_user">
                                                <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                            </select>
                                        </div>
                                        <div class="col-2 col-form-label">Buscar producto</div>
                                        <form action="" @submit.prevent="codigoProducto.length > 0 ?abrirModalVariaciones(codigoProducto):''">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="SdnCode" v-model="codigoProducto" placeholder="Código de barras" style="width: 80%;">
                                                <span class="input-group-text buscador" @click="codigoProducto.length > 0 ?abrirModalVariaciones(codigoProducto):''"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            </div>
                                        </form>
                                        
                                        <div class="col-2 col-form-label"></div>
                                        <div class="col-4"><button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Lista de productos</button></div>
                                    </div>
                                </div>
                                <div class="table-responsive scroll">   
                                    <table class="table table-bordered table-sm ">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center align-middle">#</th>
                                                <th scope="col" class="text-center align-middle">Codigo</th>
                                                <th scope="col" class="text-center align-middle">Descripcion</th>
                                                <th scope="col" class="text-center align-middle">Precio</th>
                                                <th scope="col" class="text-center align-middle" v-for="cabeceraAlm in arrayAlmacenFijo" :key="cabeceraAlm.id" >Cantidad {{cabeceraAlm.descripcion}}</th>
                                                <th scope="col" class="text-center align-middle">Descuento</th>
                                                <th scope="col" class="text-center align-middle">Total</th>
                                                <th scope="col" class="text-center align-middle">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(venta, index) in ventas" :key="venta.id">
                                                <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                <td class="text-center align-middle">{{venta.codigo}}</td>
                                                <td class="text-center align-middle">{{venta.producto}}</td>
                                                <td class="text-center align-middle" >   
                                                    <input class="form-control" type="number" id="monto1" v-model="venta.precio" @input="generarTotal(venta)">
                                                </td>
                                                <td class="text-center align-middle" v-for="cantidadAlm in venta.cantidades" :key="cantidadAlm.id"><input class="form-control" type="number" step="any"   v-model="cantidadAlm.cantidad" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle"><input class="form-control" type="number" id="monto2" step="any" v-model="venta.descuento" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle"><input class="form-control" v-model="venta.total" step="any" id="monto3" disabled></td>
                                                <td class="text-center align-middle">
                                                    <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(venta, index)"><i class="icon-trash"></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Subtotal</div>
                                        <div class="col"><input type="text" class="form-control" readonly="" id="monto4" v-model="objetoCompra.sub_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Desc.Global</div>
                                        <div class="col"><input type="text" name="DescGlobal" id="DescGlobal" class="form-control" v-model="objetoCompra.desc_global" ></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">IgvTotal</div>
                                        <div class="col"><input type="text" name="IgvTotal" id="IgvTotal" class="form-control" readonly="" v-model="objetoCompra.igv_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Total</div>
                                        <div class="col"><input type="text" name="Totales" id="Totales" step="any" class="form-control" readonly="" v-model="objetoCompra.total"></div>
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
            <div class="modal fade" tabindex="-1" role="dialog" id="modalProducto" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProductos" style="overflow-y: scroll;">
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
                                <datatable-productos  @emitirEvArrayAlm="recibirCantidadesAlmacen"  :abrirModalImagen="abrirModalImagen" :arrayAlmacenFijo="arrayAlmacenFijo" v-else-if="initiated"></datatable-productos>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modalProveedores" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProveedores">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Proveedores
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="table-responsive">
                                <spinner v-if="loading"></spinner>
                                <vue-datatable :items="arrayProveedores" :fields="cabecerasProveedor" :icono="iconos" :controlador="controlador" :funcionBoton="buscarProveedor" :factura="false"></vue-datatable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" role="dialog" id="modalInformacionCompra" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Detalle
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
                                                        <th scope="col" class="text-center align-middle">Precio</th>
                                                        <th scope="col" class="text-center align-middle">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody >
                                                    <tr v-for="(detalle, index) in arrayComprasDetalle" :key="detalle.id">
                                                        <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                        <td class="text-center align-middle">{{detalle.codigo_producto}}</td>
                                                        <td class="text-center align-middle">{{detalle.descripcion_producto}}</td>
                                                        <td class="text-center align-middle" v-for="cantidad in detalle.cantidades" :key="cantidad.id">{{cantidad.cantidad}}</td>
                                                        <td class="text-center align-middle">S/ {{detalle.precio_producto}}</td>
                                                        <td class="text-center align-middle">S/ {{detalle.total_producto}}</td>
                                                    </tr>
                                                    <tr class="total">
                                                        <th scope="row"></th>
                                                        <td></td>
                                                        <td></td>
                                                        <td v-for="alm in arrayAlmacenFijo" :key="alm.id"></td>
                                                        <td class="text-center align-middle">Total</td>
                                                        <td class="text-center align-middle">S/ {{totalItem}}</td>
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
                            </div>
                        <div class="modal-body">
                            <b-list-group>
                                <b-list-group-item button v-for="variacion in arrayVariaciones" :key="variacion.codigo" @click="agregarProducto(variacion)">
                                    <div class="d-flex justify-content-around">
                                        <span>{{variacion.variacion}}</span>
                                        <span>{{variacion.stock}}</span>
                                    </div>
                                    
                                </b-list-group-item>
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
                            <img :src="productoImagen.imagen" style="width: 18rem; border-radius: 10px;" class="img-fluid" alt=""/>
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
            arrayProveedores: [],
            arrayItems: [],
            arrayCompras: [],
            arrayComprasDetalle: [],
            codigoProducto: '',
            arrayAlmacen: [],
            objetoComprobante: {},
            objetoProductoImagen: {},
            cabecerasProveedor: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "codigo", label: "Codigo", sortable: true, class: "text-center"},
                { key: "razon", label: "Razón social", sortable: true, class: "text-center"},
                { key: "direccion", label: "Dirección", sortable: true, class: "text-center"},
                { key: "num_documento", label: "Número de documento", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ],
            iconos: 'icon-plus',
            fields: [
                { key: "id", label: "Num documento", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "responsable", label: "Responsable", sortable: true, class: "text-center"},
                { key: "fecha", label: "Fecha", sortable: true, class: "text-center"},
                { key: "total", label: "Total", sortable: true, class: "text-center"},
                { key: "actions", label: "Acciones", class: "text-center"}
            ],
            usuarioLogueado: {},
            comprobanteEscogido: 0,
            //datos de la compra
            objetoCompra: 
                {
                    cod_proveedor: '',
                    ruc_proveedor: '',
                    dir_proveedor: '',
                    razon: '',
                    id_user: null,
                    fecha: '',
                    sub_total: 0,
                    desc_global: 0,
                    igv_total: 0,
                    total: 0
                },
            controlador: 0,
            totalRows: 1,
            currentPage: 1,
            perPage: 10,
            pageOptions: [5, 10, 15],
            sortBy: "",
            sortDesc: false,
            sortDirection: "asc",
            filter: null,
            filterOn: [], // 1 - productos, 2 - proveedores, 4 -> compras
        }
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
        arrayProductos(){
            return this.$store.getters.arrayProductos;
        },
        arrayAlmacenFijo(){
            return this.$store.getters.arrayAlmacen;
        },
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.arrayComprasDetalle.length; i++){
                sum += parseFloat(this.arrayComprasDetalle[i].total_producto);
            }
            return sum.toFixed(2);
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
        this.listarCompras();
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
            this.loadingImagen = true
            $('#modalImagen').modal('show');
            axios.get(`speed/${producto.codigo}`).then(response=>{
                this.objetoProductoImagen = response.data
                this.loadingImagen = false
                this.initiatedImagen = true;
            })
        },
        limpiarTabla(){
            var ventasT = []
            this.$store.dispatch('actualizarTablaVentas', ventasT)
            localStorage.setItem('ventas', JSON.stringify(ventasT)) 
            this.controlador = 4
            this.objetoCompra.cod_proveedor =  ''
            this.objetoCompra.ruc_proveedor =  ''
            this.objetoCompra.dir_proveedor =  ''
            this.objetoCompra.razon =  ''
            this.objetoCompra.fecha =  ''
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
        generarTotal(item){
            var cantidadTotal = 0
            for(var i = 0; i < item.cantidades.length; i++){
                item.cantidades[i].cantidad = parseInt(item.cantidades[i].cantidad)
                cantidadTotal += item.cantidades[i].cantidad
            }
            item.total = item.precio * cantidadTotal
            var lista = []
            var suma = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.objetoCompra.total = suma
            this.objetoCompra.igv_total = Math.round((this.objetoCompra.sub_total * 0.18)*100)/100
            this.objetoCompra.sub_total =  Math.round((this.objetoCompra.total / 1.18)*100)/100
        },
        detalleCompra(compra){
            axios.get(`detalle_compra/${compra.id}`).then((response)=>{
                this.arrayComprasDetalle = response.data;
                $('#modalInformacionCompra').modal('show');
            })
        },
        recibirCantidadesAlmacen(almacen){
            this.arrayAlmacen = almacen
        },
        async listarItem(){
            if(this.arrayProductos.length == 0){
                this.loading = true
                await this.$store.dispatch('cargarProductos', this.usuarioLogeado.id_almacen).then(()=>{
                    this.loading = false;
                    this.initiated = true;
                });
            }
        },
        listarProveedores(){
            this.loading = true
            var urlItem = `/proveedor/listarProveedorModal`;
            axios.get(urlItem).then(response=>{
                this.arrayProveedores = response.data;
                this.loading = false;
                this.initiated = true;
                this.tablaProveedores();
            })
        },
        listarCompras(){
            var urlItem = '/cabecera_compra';
            this.loading = true
            axios.get(urlItem).then(response=>{
                this.arrayCompras = response.data;
                this.totalRows = this.arrayCompras.length;
                this.loading = false;
                this.initiated = true;
                this.miTabla();
            })
        },
        abrirModalCompra(){
            this.obtenerFecha()
            $('#modalCompra').modal('show');
        },
        abrirModalProveedores(){
            this.listarProveedores()
            this.controlador = 2
            $('#modalProveedores').modal('show');
        },
        abrirModalAgregarProducto(){
            this.listarItem();
            $('#modalProducto').modal('show');
            this.controlador = 1
        },
        eliminarProductoTabla(venta, index){
            var ventasT = this.ventas;
            ventasT.splice(index,1)
            this.$store.dispatch('actualizarTablaVentas', ventasT);
            //document.getElementById(`producto${venta.codigo}`).className = ''
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        buscarProveedor(codigo){
            axios.get(`/proveedor/${codigo}/buscarProveedor`).then((response)=>{
                this.objetoCompra.cod_proveedor = response.data[0].codigo
                this.objetoCompra.ruc_proveedor = response.data[0].num_documento
                this.objetoCompra.dir_proveedor = response.data[0].direccion
                this.objetoCompra.razon = response.data[0].razon
                $('#modalProveedores').modal('hide');
            })
        },
        insertarCabeceraCompra(){
            var me = this;
            axios.post('/cabecera_compra', this.objetoCompra).then((response)=>{
                this.id_cabecera = response.data
                var productos = this.arrayProductos
                axios.post('/detalle_compra', {'ventas': this.ventas, 'id_cabecera': this.id_cabecera}).then((response)=>{
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
                    $('#modalCompra').modal('hide');
                })
                Vue.swal(
                    'Compra concretada!',
                    'La compra se proceso correctamente!',
                    'success'
                );
                this.listarCompras();
            })
            //Ingreso multinventario completado, terminar parte tecnica
            .catch(error=>{
                Vue.swal({
                        title: 'Compra no concretada!',
                        text: 'Hubo un error al procesar la compra!',
                        icon: 'error'
                    }   
                );
            })
        },
        obtenerFecha(){
            this.objetoCompra.fecha = this.$moment().format("YYYY-MM-DD")
        }
    },
    watch:{
        ventas:{
            handler: function(){
                var lista = []
                var suma = 0
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                    
                    //this.ventas[i].almacen = this.almacen_id;
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.objetoCompra.total = suma
                this.objetoCompra.igv_total = Math.round((this.objetoCompra.sub_total * 0.18)*100)/100
                this.objetoCompra.sub_total =  Math.round((this.objetoCompra.total / 1.18)*100)/100
            },
            deep: true
        },
    }
}
</script>

<style>


.buscador{
    cursor: pointer;
}

</style>