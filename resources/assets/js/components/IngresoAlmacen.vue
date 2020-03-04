<template>
    <main class="main">
        
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <h4>Ingreso de productos</h4>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" @click="abrirModalVenta()">Nuevo</button>
                        </div>   
                    </div>
                </div>
            </div>
            <spinner v-if="loading"></spinner>
            <datatable  :arrayItems="arrayFacturas" :cabeceras="cabecerasFactura"   :controlador="controlador" :funcionBoton="verFactura" :factura="true" :idTabla="'myTable'" v-else-if="initiated">
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
                                        <option v-for="almacen in arrayAlmacen" :key="almacen.id" :value="almacen.id">{{almacen.descripcion}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Vendedor</label>
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
                                            <input type="number" class="form-control input-sm" v-model="venta.cantidad" @input="generarTotal(venta)">
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
                <div class="modal fade" tabindex="-1" role="dialog" id="modalClientes" aria-labelledby="myLargeModalLabel" aria-hidden="true" ref="tablaClientes">
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
                                    <datatable :arrayItems="arrayClientes" :cabeceras="cabecerasCliente" :icono="iconos" v-else-if="initiated" :listaVentasPadre="ventas" :controlador="10" :funcionBoton="buscarProveedor" :factura="false" :idTabla="'myTableProveedores'"></datatable>
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
            cabeceras: ['#', 'Codigo', 'Producto', 'Precio', 'Cantidad', 'Acciones'],
            cabecerasCliente: ['#', 'Código', 'Nombre', 'Tipo de documento', 'Num documento', 'Correo', 'Telef contacto', 'Acciones'],
            iconos: 'icon-plus',
            cabecerasFactura: ['#', 'Num documento', 'Almacén', 'Responsable', 'Fecha de emisión', 'Motivo', 'Observación', 'Acciones'],
            usuarioLogueado: {},
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
            id_cabecera_ingreso: null
        }
    },
    mounted(){
        //this.listarTComprobantes();
        //this.listarFacturas()
        this.listarUsuarios();
        this.controlador = 4
        $(this.$refs.vuemodal).on("hidden.bs.modal", this.limpiarTabla)
        //$(this.$refs.tablaProductos).on("hidden.bs.modal", this.limpiarTablaProductos)
        //$(this.$refs.tablaProveedores).on("hidden.bs.modal", this.limpiarTablaProveedores)
    },
    methods:{
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
            this.listarTipodeComprobante()
        },
        limpiarTablaProveedores(){
            $('#myTableProveedores').DataTable().destroy();
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
        listarUsuarios(){
            var urlItem = '/user/logeado';
            axios.get(urlItem).then(response=>{
                this.objetoFactura.id_user = response.data.id
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
                    alert('Debes registrar series')
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
            
            /*if(this.comprobanteEscogido == 0){
                alert('Debes elegir algun comprobante') 
            }else{*/
                //this.colocarFolio()
                this.seleccionarAlmacen();
                this.obtenerFecha()
                $('#modalVenta').modal('show');
            //}
            
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
            let formDatos = new FormData();
            formDatos.append('id_almacen', this.objetoIngreso.id_almacen);
            formDatos.append('id_usuario', this.objetoIngreso.id_usuario);
            formDatos.append('fecha_emision', this.objetoIngreso.fecha_emision);
            formDatos.append('motivo', this.objetoIngreso.motivo);
            formDatos.append('observacion', this.objetoIngreso.observacion);
            axios.post('/cabecera_ingreso', formDatos).then((response)=>{
                this.id_cabecera_ingreso = response.data
                axios.post('/detalle_ingreso', {'ventas': this.ventas, 'id_cabecera_ingreso': this.id_cabecera_ingreso}).then((response)=>{
                })
                $('#modalVenta').modal('hide');
                alert('Guardado correctamente');
                $('#myTable').DataTable().destroy();
                this.listarFacturas();
            })
            .catch(error=>{
                alert('Hubo un error al guardar')
            })
        },
        obtenerFecha(){
            this.objetoIngreso.fecha_emision = this.$moment().format("YYYY-MM-DD")
        }
        
    },
    watch:{
        ventas:{
            handler: function(){
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
            deep: true
        },
        comprobanteEscogido(){
            this.listarSeries()
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