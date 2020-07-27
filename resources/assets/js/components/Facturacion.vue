<template>
    <main class=" main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Facturación</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-success" @click="abrirGaveta()">Abrir gaveta</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nueva venta</button>
                        </div>   
                    </div>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <div v-else-if="initiated" class="d-flex flex-wrap justify-content-around align-items-center">
                <div v-for="comprobante in arrayComprobantes" :key="comprobante.id">
                    <input type="radio" v-model="comprobanteEscogido" v-bind:value="comprobante" @change="capturarComprobante(comprobante)">
                    <label >{{comprobante.nombre}}</label>
                </div>
            </div>
            <vue-datatable  :items="arrayFacturas" :fields="cabecerasFactura"   :controlador="4" :funcionBoton="verFactura" :factura="true" :eliminarItem="deshabilitarFactura" v-if="initiated" >
            </vue-datatable>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">Nueva venta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="card-body">
                            <form class="kt-form kt-form--label-right" action="" @submit.prevent="insertarCabecera()">
                                <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                                    <div class="row justify-content-start">
                                        <div class="col-2 col-form-label">Cod.Cliente</div>
                                        <div class="input-group col-4">
                                            <span class="input-group-text buscador" @click="(objetoFactura.cod_cliente.length > 0)?buscarCliente(objetoFactura.cod_cliente):abrirModalClientes()"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            <input type="text" class="form-control" name="SdnCode" v-model="objetoFactura.cod_cliente" placeholder="Buscar" style="width: 80%;">
                                        </div>
                                        <div class="col-2 col-form-label">Num.Doc</div>
                                        <div class="col-4"><input type="text" name="Id" class="form-control" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">{{objetoComprobante.tipo_doc}}</div>
                                            <div class="input-group col">
                                            <input type="text" name="RUC" class="form-control" :value="objetoFactura.ruc_cliente" disabled="disabled">
                                        </div>
                                        <div class="col-2 col-form-label">Dirección</div>
                                        <div class="col-4"><input type="text" name="Direccion" class="form-control" :value="objetoFactura.dir_cliente" disabled="disabled"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Razón Social</div>
                                        <div class="col-4"><input type="text" name="SdnName" class="form-control" :value="objetoFactura.razon"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Vendedor</div>
                                        <div class="col-4">
                                            <select required="required" class="form-control" v-model="objetoFactura.id_user">
                                                <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                            </select>
                                        </div>
                                     
                                        <div class="col-2 col-form-label">Fecha Emi</div>
                                        <div class="col-4"><input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoFactura.fecha"></div>
                                        <div class="w-100"></div>
                                        <div class="col-2 col-form-label">Tipo Venta</div>
                                        <div class="col-4">
                                            <select name="PedTipo" class="form-control" v-model="objetoFactura.tipo_venta">
                                                <option value="A">Artículo</option>
                                                <option value="S">Servicio</option>
                                            </select>
                                        </div>
                                        <div class="col-2 col-form-label">Folio</div>
                                        <div class="col-2">
                                            <select id="selectSeries" required="required" class="form-control" v-model="objetoFactura.id_serie" @change="colocarFolio()">
                                                <option v-for="serie in arraySeries" :value="serie.id" :key="serie.key">{{serie.serie}}</option>
                                            </select>
                                        </div>
                                        <div class="col-2"><input type="text" name="Folio" class="form-control"  placeholder="Folio" v-model="objetoFactura.folio"></div>
                                        <div class="w-100"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ml-auto">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalAgregarProducto()">Agregar productos</button>
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
                                                <th scope="col" class="text-center align-middle">Cantidad</th>
                                                <th scope="col" class="text-center align-middle">Descuento</th>
                                                <th scope="col" class="text-center align-middle">Almacen</th>
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
                                                <td class="text-center align-middle"><input class="form-control" type="number" step="any" v-model="venta.cantidad" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle"><input class="form-control" type="number" id="monto2" step="any" v-model="venta.descuento" @input="generarTotal(venta)"></td>
                                                <td class="text-center align-middle">
                                                    <select v-model="venta.almacen" class="form-control">
                                                        <option disabled value="">Escoje un almacén</option>
                                                        <option v-for="almacen in arrayAlmacenFijo" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                                    </select>
                                                </td>
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
                                        <div class="col"><input type="text" class="form-control" readonly="" id="monto4" v-model="objetoFactura.sub_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Desc.Global</div>
                                        <div class="col"><input type="text" name="DescGlobal" id="DescGlobal" class="form-control" v-model="objetoFactura.desc_global" ></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">IgvTotal</div>
                                        <div class="col"><input type="text" name="IgvTotal" id="IgvTotal" class="form-control" readonly="" v-model="objetoFactura.igv_total"></div>
                                        <div class="w-100"></div>
                                        <div class="col-6"></div>
                                        <div class="col-2 col-form-label">Total</div>
                                        <div class="col"><input type="text" name="Totales" id="Totales" step="any" class="form-control" readonly="" v-model="objetoFactura.total"></div>
                                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTipoPago">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="modal fade" id="modalTipoPago"  role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tipo de pago</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" @submit.prevent="insertarCabecera()">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Total</label>
                                            <input class="form-control" disabled :value="'S/ '+objetoFactura.total">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label >Tipo de pago</label>
                                            <div class="col-sm-10">
                                                <div class="form-check" v-for="tipoPago in arrayObjetosTipoPago" :key="tipoPago.id">
                                                    <input class="form-check-input" type="radio" :value="tipoPago.valor" v-model="objetoFactura.tipo_pago" @change="totalPagar()" checked>
                                                    <label class="form-check-label" >
                                                        {{tipoPago.tipo}}
                                                    </label>
                                                </div>
                                                <label v-if="objetoFactura.tipo_pago == 'tarjeta'">Comisión</label>
                                                <input type="number" class="form-control" step="any" id="monto5" v-if="objetoFactura.tipo_pago == 'tarjeta'" v-model="comision">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar venta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade"  role="dialog" id="modalProducto" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaProductos" style="overflow-y: scroll;">
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
            <div class="modal fade"  role="dialog" id="modalClientes" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaClientes">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Clientes
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <b-button size="sm" variant="primary" @click="abrirModalRegistro()">
                                Nuevo cliente
                            </b-button>
                            <div class="table-responsive">
                                <spinner v-if="loading"></spinner>
                                <vue-datatable :items="arrayClientes" :fields="cabecerasCliente" :icono="iconos" v-else-if="initiated" :listaVentasPadre="ventas" :controlador="10" :funcionBoton="buscarCliente" :factura="false" :idTabla="'myTableClientes'"></vue-datatable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade"  role="dialog" id="modalInformacionFact" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="modalInformacionFact">
                <div class="modal-dialog modal-xl">
                    <div class="card-body">
                        <div class="modal-content" >
                            <div class="modal-header">
                                Detalle factura
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
                                                    <th scope="col" class="text-center align-middle">Almacen</th>
                                                    <th scope="col" class="text-center align-middle">Cantidad</th>
                                                    <th scope="col" class="text-center align-middle">Precio</th>
                                                    <th scope="col" class="text-center align-middle">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody >
                                                <tr v-for="(detalle, index) in arrayFacturaDetalle" :key="detalle.id">
                                                    <th scope="row" class="text-center align-middle">{{index+1}}</th>
                                                    <td class="text-center align-middle">{{detalle.codigo_producto}}</td>
                                                    <td class="text-center align-middle">{{detalle.descripcion_producto}}</td>
                                                    <td class="text-center align-middle">{{detalle.almacen}}</td>
                                                    <td class="text-center align-middle">{{detalle.cantidad_producto}}</td>
                                                    <td class="text-center align-middle">S/ {{detalle.precio_producto}}</td>
                                                    <td class="text-center align-middle">S/ {{detalle.total_producto}}</td>
                                                </tr>
                                                <tr class="total">
                                                    <th scope="row"></th>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
            <div class="modal fade bd-example-modal-lg"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCantidades">
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
            <div class="modal fade bd-example-modal-lg"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modalCliente">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Nuevo cliente</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            
                                <form
                                    id="formA"
                                    action
                                    @submit.prevent="agregarCliente()"
                                    enctype="multipart/form-data"
                                    >
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="codigo">Codigo</label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model="objetoCliente.codigo"
                                                placeholder="Ingrese el codigo del cliente"
                                                />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="razon">Razón social</label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model="objetoCliente.razon"
                                                placeholder="Ingresa la razón social"
                                                />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="direccion">Dirección</label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model="objetoCliente.direccion"
                                                placeholder="Ingrese la direccion del cliente"
                                                />
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label >Tipo de documento</label>
                                                <select class="form-control" v-model="objetoCliente.id_tipo_doc">
                                                    <option v-for="documento in arrayDocumentos" :key="documento.id" :value="documento.id">{{documento.tipo_doc}}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="tipo_documento">Número de documento</label>
                                                <input
                                                type="text"
                                                class="form-control"
                                                v-model="objetoCliente.num_documento"
                                                placeholder="Ingrese el número de documento del cliente"
                                                />
                                            </div>
                                        </div>
                                        <b-button size="sm" @click="cerrarModalClientes()">Cerrar</b-button>
                                        <b-button size="sm"  type="submit" variant="primary">Guardar</b-button>
                                        </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--<b-modal size="lg" hide-footer v-model="estadoModalClientes" id="modalCliente" title="Nuevo cliente" @hidden="resetModal" :no-enforce-focus="true">
            
                </b-modal> -->
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
            estadoModalClientes: false,
            initiated: false,
            loading: false,
            initiatedImagen: false,
            loadingImagen: false,
            arrayClientes: [],
            arrayItems: [],
            arrayFacturas: [],
            show: false,
            arrayFacturaDetalle: [],
            arrayComprobantes: [],
            arraySeries: [],
            ventas: [],
            arrayAlmacen: [],
            objetoComprobante: {},
            objetoProductoImagen: {},
            cabecerasCliente: [
                { key: "index", label: "#", sortable: true, sortDirection: "desc" , class: "text-center"},
                { key: "codigo", label: "Codigo", sortable: true,class: "text-center", class: "text-center"},
                { key: "razon", label: "Razon social", sortable: true, class: "text-center"},
                { key: "direccion", label: "Direccion", sortable: true, class: "text-center"},
                { key: "num_documento", label: "N° documento", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones" , class: "text-center"}
            ],
            iconos: 'icon-plus',
            cabecerasFactura: [
                { key: "num_doc", label: "Num doc", sortable: true, sortDirection: "desc", class: "text-center" },
                { key: "usuario", label: "Vendedor", sortable: true,class: "text-center", class: "text-center"},
                { key: "serie", label: "Serie", sortable: true, class: "text-center"},
                { key: "folio", label: "Folio", sortable: true, class: "text-center"},
                { key: "fecha", label: "Fecha", sortable: true, class: "text-center"},
                { key: "total", label: "Total", sortable: true, class: "text-center"},
                { key: "actions", label: "acciones" , class: "text-center"}
            ],
            usuarioLogueado: {},
            comprobanteEscogido: {},
            tipoDocumento: null,
            //datos de la factura
            almacen_id: '',
            objetoFactura: 
                {
                    id_serie: null,
                    id_tipo_comprobante: null,
                    id_almacen: null,
                    cod_cliente: '',
                    ruc_cliente: '',
                    dir_cliente: '',
                    razon: '',
                    id_user: null,
                    fecha: '',
                    tipo_venta: 'A',
                    tipo_pago: 'efectivo',
                    serie: 'F001',
                    folio: '',
                    sub_total: 0,
                    desc_global: 0,
                    igv_total: 0,
                    total: 0
                },
            arrayObjetosTipoPago: [
                {tipo: 'Efectivo', valor: 'efectivo'},
                {tipo: 'Tarjeta', valor: 'tarjeta'},
                {tipo: 'Cheque', valor: 'cheque'}
            ],
            objetoCliente:{
                id_tipo_doc: null,
                codigo: null,
                razon: null,
                direccion: null,
                num_documento: null
            },
            pagarSinComision: 0,
            comision: 0.04,
            controlador: 0, // 1 - productos, 2 - clientes, 4 -> facturas
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
        arrayDocumentos(){
            return this.$store.getters.arrayDocumentos;
        },
        totalItem: function(){
            let sum = 0;
            for(let i = 0; i < this.arrayFacturaDetalle.length; i++){
                sum += parseFloat(this.arrayFacturaDetalle[i].total_producto);
            }
            return sum.toFixed(2);
        }
    },
    mounted(){
        this.objetoFactura.id_user = this.usuarioLogeado.id
        this.objetoFactura.id_almacen = this.usuarioLogeado.id_almacen
        this.almacen_id = this.usuarioLogeado.id_almacen;
        axios.get(`/cierre_caja/${this.almacen_id}/verificarEstadoCaja`).then((response)=>{
            var contador = 0;
            for(var i = 0; i < response.data.length; i++){
                if(response.data[i].estado == 'abierto' && response.data[i].fecha == this.$moment().format("YYYY-MM-DD")){
                     contador += 1
                }
            }
            if(contador > 0){
                this.listarTComprobantes();
                this.controlador = 4
                $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
            }else{
                Vue.swal({
                    title: 'No hay alguna caja abierta',
                    text: '¡Debes abrir caja primero!',
                    icon: 'error'
                });
            }
        })
    },
    methods:{
        //clientes
        abrirModalRegistro(){
            $('#modalCliente').modal('show');
            //this.$bvModal.show('modalCliente')
        },
        agregarCliente(){
            axios.post('/cliente', this.objetoCliente).then(response=>{
                this.listarClientes();
                this.objetoCliente.id_tipo_doc = null
                this.objetoCliente.codigo = null
                this.objetoCliente.razon = null
                this.objetoCliente.direccion = null
                this.objetoCliente.num_documento = null 
                $('#modalCliente').modal('hide')
            }).catch(err=>{
                console.log(err);
            })
        },
        cerrarModalClientes(){
            this.objetoCliente.id_tipo_doc = null
            this.objetoCliente.codigo = null
            this.objetoCliente.razon = null
            this.objetoCliente.direccion = null
            this.objetoCliente.num_documento = null 
            $('#modalCliente').modal('hide')
        },
        //termina clientes
        abrirModalImagen(producto){
            this.loadingImagen = true
            $('#modalImagen').modal('show');
            axios.get(`speed/${producto.codigo}`).then(response=>{
                this.objetoProductoImagen = response.data
                this.loadingImagen = false
                this.initiatedImagen = true;
            })
        },
        totalPagar(){
            var comisionT = this.objetoFactura.total * this.comision
            if(this.objetoFactura.tipo_pago == 'tarjeta'){
                this.objetoFactura.total =  this.objetoFactura.total + comisionT
            }else{
                var lista = []
                var suma = 0
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.objetoFactura.total = suma
                
            }
        },
        capturarComprobante(item){
            this.objetoComprobante = item
        },
        limpiarTabla(){
            console.log('limpiando...')
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoFactura.cod_cliente =  ''
            this.objetoFactura.ruc_cliente =  ''
            this.objetoFactura.dir_cliente =  ''
            this.objetoFactura.razon =  ''
            this.objetoFactura.fecha =  ''
            this.objetoFactura.tipo_pago = 'efectivo'
            this.objetoFactura.tipo_venta =  'A'
            this.objetoFactura.folio = ''
            this.comision = 0.04
        },
        generarTotal(item){
            item.total = (item.precio * item.cantidad) - item.descuento
            var lista = []
            var suma = 0
            var descuento_global = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
                descuento_global += Number(this.ventas[i].descuento)
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.objetoFactura.total = suma
            this.objetoFactura.igv_total = Math.round((this.objetoFactura.sub_total * 0.18)*100)/100
            this.objetoFactura.sub_total =  Math.round((this.objetoFactura.total / 1.18)*100)/100
            this.objetoFactura.desc_global = descuento_global;
        },
        recibirVenta(venta){
            this.ventas = venta
        },
        recibirCantidadesAlmacen(almacen){
            this.arrayAlmacen = almacen
        },
        async listarItem(){
            //if(this.arrayProductos.length == 0){
                this.loading = true
                await this.$store.dispatch('cargarProductos').then(()=>{
                    
                    this.loading = false;
                    this.initiated = true;
                });
            //}
        },
        listarClientes(){
            this.loading = true
            var urlItem = `/cliente/${this.objetoComprobante.id_tipo_doc}/listarClientesModal`;
            axios.get(urlItem).then(response=>{
                this.arrayClientes = response.data;
                this.loading = false;
                this.initiated = true;
            })
        },
        listarFacturas(){
            var urlItem = '/c_fact';
            this.loading = true
            axios.get(urlItem).then(response=>{
                this.arrayFacturas = response.data;
                this.loading = false;
                this.initiated = true;
            })
        },
        listarTipodeComprobante(){
            axios.get(`/c_fact/${this.comprobanteEscogido.id}/${this.almacen_id}/mostrarPorAlmacen`).then(response=>{
                this.arrayFacturas = response.data;
                this.loading = false;
                this.initiated = true;
            })
        },
        listarTComprobantes(){
            var urlItem = '/tipo_comprobante/obtenerComprobantes';
            this.loading = true;
            axios.get(urlItem).then(response=>{
                this.arrayComprobantes = response.data;
                this.listarTipodeComprobante()
            })
        },
        listarSeries(){
            this.loading = true
            axios.get(`/serie_comprobante/${this.comprobanteEscogido.id}/${this.usuarioLogeado.id_almacen}/listarSeries`).then(response=>{
                if(response.data.length > 0){
                    this.arraySeries = response.data;
                    this.objetoFactura.id_serie = response.data[0].id;
                    this.loading = false;
                    this.initiated = true;
                }else{
                    Vue.swal({
                        title: 'Debes registrar series',
                        text: '¡No hay series registradas para este tipo de comprobante!',
                        icon: 'error'
                    });
                }
            })
        },
        colocarFolio(){
            var combo = document.getElementById("selectSeries");
            var selected = combo.options[combo.selectedIndex].text;
            this.objetoFactura.serie = selected;
        },
        abrirModalVenta(){
            if(this.comprobanteEscogido.id == 0){
                Vue.swal({
                    title: 'Debes elegir un tipo de comprobante',
                    text: '¡No tienes nigún tipo de comprobante seleccionado!',
                    icon: 'error'
                }); 
            }else{
                this.colocarFolio()
                this.obtenerFecha()
                $('#modalVenta').modal('show');
            }
        },
        abrirModalClientes(){
            this.listarClientes()
            this.controlador = 2
            $('#modalClientes').modal('show');
        },
        abrirModalAgregarProducto(){
            this.listarItem();
            $('#modalProducto').modal('show');
            this.controlador = 1
        },
        eliminarProductoTabla(venta, index){
            this.ventas.splice(index,1)
            //document.getElementById(`producto${venta.codigo}`).className = ''
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
        },
        buscarCliente(codigo){
            axios.get(`/cliente/${codigo}/buscarCliente`).then((response)=>{
                this.objetoFactura.cod_cliente = response.data[0].codigo
                this.objetoFactura.ruc_cliente = response.data[0].num_documento
                this.objetoFactura.dir_cliente = response.data[0].direccion
                this.objetoFactura.razon = response.data[0].razon
                $('#modalClientes').modal('hide');
            })
        },
        verFactura(item){
            axios.get(`d_fact/${item.num_doc}`).then((response)=>{    
                this.arrayFacturaDetalle = response.data;
                $('#modalInformacionFact').modal('show')
            })
        },
        imprimirBoleta(factura){
            //console.log(this.ventas)
            const RUTA_API = "http://localhost:8000";
            
            let impresora = new Impresora(RUTA_API);
                        
            impresora.setFontSize(1, 1);
            impresora.setEmphasize(0);
            impresora.setAlign("center");
            impresora.write("SPEEDCUBER PERU\n");
            //impresora.write("ROJAS RIOS VANESSA KATHERINE\n");
            impresora.write("AV. ARENALES 1624 Tienda 34\n");
            impresora.write("LIMA - LIMA - LINCE\n");
            impresora.write("RUC: 10457782417\n");
            impresora.write("Telefono: 01-7505980\n");
            impresora.write(`${this.comprobanteEscogido.nombre} N: ${factura.serie} - ${factura.folio}\n`);
            impresora.write(`Fecha/Hora: ${factura.created_at}\n`);
            impresora.write("DATOS DEL CLIENTE:\n");
            if(this.objetoFactura.razon){
                impresora.write(`Nombre: ${this.objetoFactura.razon}\n`);
            }
            if(this.objetoFactura.cod_cliente){
                impresora.write(`Direccion: ${this.objetoFactura.dir_cliente}\n`);
            }
            if(factura.ruc_cliente){
                impresora.write(`DNI: ${factura.ruc_cliente}\n`);
            }
                impresora.write(`FORMA DE PAGO: ${this.objetoFactura.tipo_pago}\n`);
            impresora.write("DATOS DEL PRODUCTO:\n");
            impresora.write("CANTID.:  ");
            impresora.write("P.VENTA:  ");
            impresora.write("TOTAL:  \n");
            impresora.write("--------------------------------\n");
            for(var s = 0; s < this.ventas.length; s++){
                impresora.setAlign("center");
                impresora.write(`${this.ventas[s].producto}\n`);
                impresora.write(`${this.ventas[s].cantidad}UNIDAD  `);
                impresora.write(`${parseFloat(this.ventas[s].precio).toFixed(2)}  `);
                impresora.setAlign("right");
                impresora.write(`${parseFloat(this.ventas[s].total).toFixed(2)}\n`);
                impresora.write("--------------------------------\n");
            }
            impresora.setAlign("right");
            impresora.write(`SUBTOTAL: S/ ${this.objetoFactura.sub_total}\n`);
            impresora.write(`IGV 18%: ${this.objetoFactura.igv_total}\n`);
            impresora.write(`TOTAL: S/ ${parseFloat(this.objetoFactura.total).toFixed(2)}\n\n`);
            impresora.setAlign("center");
            impresora.write("***Gracias por su compra***");
            impresora.feed(3);
            impresora.cut();
            impresora.cutPartial(); // Pongo este y también cut porque en ocasiones no funciona con cut, solo con cutPartial
            impresora.cash();
            impresora.end()
                .then(valor => {
                    console.log(valor)
                })
        },
        abrirGaveta(){
            const RUTA_API = "http://localhost:8000";
            
            let impresora = new Impresora(RUTA_API);
            impresora.cash();
            impresora.end()
                .then(valor => {
                    console.log(valor)
                })
        },
        insertarCabecera(){
            this.show = true
            var me = this;
            axios.post('/c_fact', {'ventas': this.ventas, 'objeto_factura': this.objetoFactura}).then((response)=>{
                var factura = response.data
                //console.log('Factura: ', factura)
                //console.log('folio', factura.folio)
                this.show = false
                var productos = this.arrayProductos
                //axios.post('/d_fact', {'ventas': this.ventas, 'id_cabecera': this.id_cabecera}).then((response)=>{
                    for(var k = 0; k < this.ventas.length; k++){
                        for(var l = 0; l < productos.length; l++){
                            if(this.ventas[k].codigo == productos[l].codigo){
                                productos[l].stock = productos[l].stock - this.ventas[k].cantidad
                            }
                        }
                    }
                    this.$store.dispatch('actualizarProductos', productos)
                    
                //})
                this.imprimirBoleta(factura);
                Vue.swal(
                    'Venta concretada!',
                    'La venta se proceso correctamente!',
                    'success'
                ).then((result) => {
                if (result.value) {
                    $('#modalTipoPago').modal('hide')
                    $('#modalVenta').modal('hide');
                   this.listarTipodeComprobante();
                    
                }
            });
                
            })
            .catch(error=>{
                this.show = false
                Vue.swal({
                        title: `${error.response.data.error}`,
                        text: 'Hubo un error al procesar la venta!',
                        icon: 'error'
                    }   
                );
            })
        },
        deshabilitarFactura(item, index){
            
            Vue.swal({
                    title: `Está seguro de deshabilitar el documento ${item.num_doc}?`,
                    text: 'No podrás volver a habilitar este documento',
                    icon: 'warning'
                }
                ).then((result) => {
                if (result.value) {
                    this.show = true
                    let formDatos = new FormData();
                    formDatos.append('fecha', item.fecha)
                    formDatos.append('folio', item.folio);
                    formDatos.append('num_doc', item.num_doc)
                    formDatos.append('serie', item.serie);
                    formDatos.append('total', item.total)
                    formDatos.append('id_almacen', item.id_almacen);
                    formDatos.append('id_tipo_comprobante', item.id_tipo_comprobante);
                    formDatos.append('tipo_pago', item.tipo_pago);
                    formDatos.append('estado', item.estado);
                    formDatos.append("_method", "put");
                    axios.post(`c_fact/${item.num_doc}/deshabilitarFactura`, formDatos).then(response=>{
                        this.show = false
                        this.arrayFacturas.splice(index,1) 
                    }).catch(err=>{
                        console.log(err)
                    })
                 
                }
            })
            
            
        },
        eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.num_doc}?`)){
                axios.delete(`/c_fact/${item.num_doc}`)
                    .then(()=>{
                        this.arrayFacturas.splice(index,1)          
                    })
            }
        },
        obtenerFecha(){
            this.objetoFactura.fecha = this.$moment().format("YYYY-MM-DD")
        }
    },
    watch:{
        ventas:{
            handler: function(){
                var lista = []
                var suma = 0
                var descuento_global = 0
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                    this.ventas[i].almacen = this.almacen_id;
                    descuento_global += Number(this.ventas[i].descuento)
                    
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.objetoFactura.total = suma
                this.objetoFactura.igv_total = Math.round((this.objetoFactura.sub_total * 0.18)*100)/100
                this.objetoFactura.sub_total =  Math.round((this.objetoFactura.total / 1.18)*100)/100
                this.objetoFactura.desc_global = descuento_global;
            },
            deep: true
        },
        comision(){
            var lista = []
            var suma = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
                this.ventas[i].almacen = this.almacen_id;
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.objetoFactura.total = Number(suma.toFixed(2));
            var comisionT = this.objetoFactura.total * this.comision
            if(this.objetoFactura.tipo_pago == 'tarjeta'){
                this.objetoFactura.total =  this.objetoFactura.total + comisionT
            }
        },
        comprobanteEscogido(){
            this.listarSeries()
            this.objetoFactura.id_tipo_comprobante = this.comprobanteEscogido.id;
            this.listarTipodeComprobante()

        }
    }
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
