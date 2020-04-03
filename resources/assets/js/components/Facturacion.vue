<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Factura</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo</button>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="probarAlert()">probar</button>
                        </div>   
                    </div>
                </div>
            </div>
            
            <div class="d-flex flex-wrap justify-content-around align-items-center">
                <div v-for="comprobante in arrayComprobantes" :key="comprobante.id">
                    <input type="radio" v-model="comprobanteEscogido" v-bind:value="comprobante.id" @change="capturarComprobante(comprobante)">
                    <label >{{comprobante.nombre}}</label>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <datatable  :arrayItems="arrayFacturas" :cabeceras="cabecerasFactura"   :controlador="controlador" :funcionBoton="verFactura" :factura="true" :idTabla="'myTable'" v-else-if="initiated">
            </datatable>
            <div class="modal fade bd-example-modal-lg1" id="modalVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="vuemodal" style="overflow-y: scroll;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ventas - Pedidos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="card-body">
                        <form class="kt-form kt-form--label-right" action="" @submit.prevent="insertarCabecera()">
                        <div class="kt-portlet__body" data-scroll="true" data-height="200" data-scrollbar-shown="true">
                            <div class="row justify-content-start">

                                <div class="col-2 col-form-label">Cod.Cliente</div>
                                    <div class="input-group col">
                                        <div class="btn input-group-prepend btn_subbuscar" id="Btn_SubBuscar" idsubform="1">
                                            <span class="input-group-text buscador" @click="(objetoFactura.cod_cliente.length > 0)?buscarCliente(objetoFactura.cod_cliente):abrirModalClientes()"><i class="fa fa-search" aria-hidden="true" ></i ></span>
                                            <input type="text" class="form-control" name="SdnCode" v-model="objetoFactura.cod_cliente" placeholder="Escriba ó Busque...">
                                        </div>
                                </div>
                                <div class="col-2 col-form-label">Num.Doc</div>
                                <div class="col"><input type="text" name="Id" class="form-control" disabled="disabled"></div>
                                <div class="w-100"></div>

                                <div class="col-2 col-form-label">{{objetoComprobante.tipo_doc}}</div>
                                    <div class="input-group col">
                                    <input type="text" name="RUC" class="form-control" :value="objetoFactura.ruc_cliente" disabled="disabled">
                                </div>
                                <div class="col-2 col-form-label">Dirección</div>
                                <div class="col"><input type="text" name="Direccion" class="form-control" :value="objetoFactura.dir_cliente" disabled="disabled"></div>
                                <div class="w-100"></div>

                                <div class="col-2 col-form-label">Razón Social</div>
                                <div class="col"><input type="text" name="SdnName" class="form-control" :value="objetoFactura.razon"></div>

                                <div class="w-100"></div>
                          

                                <div class="col-2 col-form-label">Vendedor</div>
                                <div class="col-3">
                                    <select required="required" class="form-control" v-model="objetoFactura.id_user">
                                        <option v-for="usuario in arrayUsuarios" :value="usuario.id" :key="usuario.key">{{usuario.usuario}}</option>
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Fecha Emi</div>
                                <div class="col"><input type="date" name="FechaReg"  id="FechaReg" class="form-control" v-model="objetoFactura.fecha"></div>

                                <div class="w-100"></div>
                                <div class="col-2 col-form-label">Tipo Venta</div>
                                <div class="col-3">
                                    <select name="PedTipo" class="form-control" v-model="objetoFactura.tipo_venta">
                                        <option value="A">Artículo</option>
                                        <option value="S">Servicio</option>
                                    </select>
                                </div>
                                <div class="col-1"></div>
                                <div class="col-2 col-form-label">Folio</div>
                                <div class="col-2">
                                    <!--<input type="text" name="Serie" class="form-control" placeholder="Serie" v-model="objetoFactura.serie">-->
                                    <select id="selectSeries" required="required" class="form-control" v-model="objetoFactura.id_serie" @change="colocarFolio()">
                                        <option v-for="serie in arraySeries" :value="serie.id" :key="serie.key">{{serie.serie}}</option>
                                    </select>
                                </div>
                                <div class="col-2"><input type="text" name="Folio" class="form-control"  placeholder="Folio" v-model="objetoFactura.folio"></div>


                                <div class="w-100"></div>
                            </div>

                            <input type="button" name="addRow" id="addRow" value="Agrega1" style="display: none">

                            
                                <table id="D001" class="display pageResize D001" style="width:100%"></table>
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
                                            <input type="number" v-model="venta.precio" @input="generarTotal(venta)">
                                        </td>
                                        <td class="text-center align-middle"><input type="number" v-model="venta.cantidad" @input="generarTotal(venta)"></td>
                                        <td class="text-center align-middle"><input type="number" v-model="venta.descuento" @input="generarTotal(venta)"></td>
                                        <td class="text-center align-middle">
                                            <select v-model="venta.almacen">
                                                <option disabled value="">Escoje un almacén</option>
                                                <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                            </select>
                                        </td>
                                        <td class="text-center align-middle"><input v-model="venta.total" disabled></td>
                                        <td class="text-center align-middle">
                                            <span class="btn btn-danger btn-sm boton" @click="eliminarProductoTabla(index)"><i class="icon-trash"></i></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                                <div class="w-100"></div>
                                <div class="col-6"></div>
                                <div class="col-2 col-form-label">Subtotal</div>
                                <div class="col"><input type="text" class="form-control" readonly="" v-model="objetoFactura.sub_total"></div>

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
                                <div class="col"><input type="text" name="Totales" id="Totales" class="form-control" readonly="" v-model="objetoFactura.total"></div>
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
                                    <datatable  :arrayItems="arrayItems" :cabeceras="cabeceras" :icono="iconos" @emitirEvProductos="recibirVenta" :listaVentasPadre="ventas" :controlador="1" :factura="false" :idTabla="'myTableProductos'" v-else-if="initiated"></datatable>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" tabindex="-1" role="dialog" id="modalClientes" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaClientes">
                    <div class="modal-dialog modal-xl">
                        <div class="card-body">
                            <div class="modal-content" >
                                <div class="modal-header">
                                    Clientes
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <spinner v-if="loading"></spinner>
                                    <datatable :arrayItems="arrayClientes" :cabeceras="cabecerasCliente" :icono="iconos" v-else-if="initiated" :listaVentasPadre="ventas" :controlador="controlador" :funcionBoton="buscarCliente" :factura="false" :idTabla="'myTableClientes'"></datatable>
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
            arrayFacturas: [],
            arrayUsuarios: [],
            arrayComprobantes: [],
            arraySeries: [],
            arrayAlmacen: [],
            ventas: [],
            objetoComprobante: {},
            cabeceras: ['Acciones', '#', 'Codigo', 'Producto', 'Precio', 'Cantidad'],
            cabecerasCliente: ['Acciones', '#', 'Codigo', 'Razón social', 'Dirección', 'Numero de documento'],
            iconos: 'icon-plus',
            cabecerasFactura: ['Acciones', '#', 'ID', 'Razón social', 'Serie', 'Folio', 'Fecha', 'Total'],
            usuarioLogueado: {},
            comprobanteEscogido: '',
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
                    serie: 'F001',
                    folio: '',
                    sub_total: 0,
                    desc_global: 0,
                    igv_total: 0,
                    total: 0
                },
            controlador: 0, // 1 - productos, 2 - clientes, 4 -> facturas
            objetoDetalleFact: {}
        }
    },
    mounted(){
        this.listarUsuarios();
    },
    methods:{
        probarAlert(){
                // Use sweetalret2
                Vue.swal({
                    title: 'Good job!',
                    text: 'You clicked the button!',
                    icon: 'success'
                });
            
        },
        capturarComprobante(item){
            this.objetoComprobante = item
        },
        limpiarTabla(){
            this.ventas = []
            localStorage.setItem('ventas', JSON.stringify(this.ventas)) 
            this.controlador = 4
            this.objetoFactura.id_tipo_comprobante =  null
            this.objetoFactura.cod_cliente =  ''
            this.objetoFactura.ruc_cliente =  ''
            this.objetoFactura.dir_cliente =  ''
            this.objetoFactura.razon =  ''
            this.objetoFactura.fecha =  ''
            this.objetoFactura.tipo_venta =  'A'
            this.objetoFactura.folio = ''
            
        },
        limpiarTablaProductos(){
            $('#myTableProductos').DataTable().destroy();
            this.listarTipodeComprobante()
        },
        limpiarTablaClientes(){
            $('#myTableClientes').DataTable().destroy();
            this.listarTipodeComprobante()
        },
        generarTotal(item){
            item.total = item.precio * item.cantidad
            var lista = []
            var suma = 0
            for(var i = 0; i < this.ventas.length; i++){
                lista.push(this.ventas[i].total)
            }
            suma = lista.reduce((a, b) => a + b, 0)
            this.objetoFactura.sub_total = suma
            this.objetoFactura.igv_total = Math.round((this.objetoFactura.sub_total * 0.18)*100)/100
            this.objetoFactura.total =  Math.round((this.objetoFactura.sub_total + this.objetoFactura.igv_total)*100)/100 
           
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
        listarFacturas(){
            var urlItem = '/c_fact';
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
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoFactura.id_user = response.data.id
                this.objetoFactura.id_almacen = response.data.id_almacen
                this.almacen_id = response.data.id_almacen;
                axios.get(`/cierre_caja/${this.almacen_id}/verificarEstadoCaja`).then((response)=>{
                    //this.arrayCajas = response.data;
                    var contador = 0;
                    for(var i = 0; i < response.data.length; i++){
                        if(response.data[i].estado == 'abierto' && response.data[i].fecha == this.$moment().format("YYYY-MM-DD")){
                            contador += 1
                            //break;
                        }
                    }
                    if(contador > 0){
                        this.listarTComprobantes();
                        this.listarFacturas()
                        
                        this.controlador = 4
                        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
                        $(this.$refs.tablaProductos).on("hidden.bs.modal", this.limpiarTablaProductos)
                        $(this.$refs.tablaClientes).on("hidden.bs.modal", this.limpiarTablaClientes)
                    }else{
                        Vue.swal({
                            title: 'No hay alguna caja abierta',
                            text: '¡Debes abrir caja primero!',
                            icon: 'error'
                        });
                    }
                })
            })
            var urlItem = '/user';
            axios.get(urlItem).then(response=>{
                this.arrayUsuarios = response.data;
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
        /*eliminarItem(item, index){
            if(confirm(`Está seguro de eliminar el item ${item.codigo}?`)){
                axios.delete(`/producto/${item.id}`)
                    .then(()=>{
                        this.arrayItems.splice(index,1)
                        
                    })
                    $( function () {
                        $('table.display').DataTable().destroy();
                    } );
                    this.listarItem();
            }
        },*/
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
            
            if(this.comprobanteEscogido == 0){
                Vue.swal({
                    title: 'Debes elegir un tipo de comprobante',
                    text: '¡No tienes nigún tipo de comprobante seleccionado!',
                    icon: 'error'
                }); 
            }else{
                this.colocarFolio()
                this.seleccionarAlmacen();
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
            this.listarItem()
            this.controlador = 1
            $('#modalProducto').modal('show');
        },
        seleccionarAlmacen(){
            let me= this;
            var url='/almacen';
            axios.get(url).then(function (response){
                me.arrayAlmacen = response.data;
            })
        },
        eliminarProductoTabla(index){
            this.ventas.splice(index,1)
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
            var me = this;
            axios.post('/c_fact', this.objetoFactura).then((response)=>{
                this.id_cabecera = response.data
                axios.post('/d_fact', {'ventas': this.ventas, 'id_cabecera': this.id_cabecera}).then((response)=>{
                })
                $('#modalVenta').modal('hide');
                Vue.swal(
                    'Venta concretada!',
                    'La venta se proceso correctamente!',
                    'success'
                );
                $('#myTable').DataTable().destroy();
                this.listarTipodeComprobante();
            })
            .catch(error=>{
                Vue.swal({
                        title: 'Venta no concretada!',
                        text: 'Hubo un error al procesar la venta!',
                        icon: 'error'
                    }   
                );
            })
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
                for(var i = 0; i < this.ventas.length; i++){
                    lista.push(this.ventas[i].total)
                    this.ventas[i].almacen = this.almacen_id;
                }
                suma = lista.reduce((a, b) => a + b, 0)
                this.objetoFactura.sub_total = suma
                this.objetoFactura.igv_total = Math.round((this.objetoFactura.sub_total * 0.18)*100)/100
                this.objetoFactura.total =  Math.round((this.objetoFactura.sub_total + this.objetoFactura.igv_total)*100)/100 
                },
            deep: true
        },
        comprobanteEscogido(){
            this.listarSeries()
            this.objetoFactura.id_tipo_comprobante = this.comprobanteEscogido;
            $( function () {
                $('#myTable').DataTable().destroy();
            } );
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
